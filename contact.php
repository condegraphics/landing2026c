<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Endpoint propio de contacto para Hostinger.
 *
 * El envío usa PHPMailer con el SMTP Relay de Google Workspace. La regla de
 * Google autoriza la IP de Hostinger y no se guardan credenciales en el sitio.
 */

const RECIPIENT_EMAIL = 'info@condegraphics.com';
const SENDER_EMAIL = 'info@condegraphics.com';
const SENDER_NAME = 'Conde Graphics';
const SITE_URL = 'https://www.condegraphics.com/';
const MAX_NAME_LENGTH = 120;
const MAX_EMAIL_LENGTH = 254;
const MAX_PHONE_LENGTH = 60;
const MAX_WEBSITE_LENGTH = 254;
const MAX_MESSAGE_LENGTH = 5000;

header('X-Robots-Tag: noindex, nofollow', true);
header('Referrer-Policy: strict-origin-when-cross-origin', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST', true);
    exit('Método no permitido.');
}

function post_value(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function limit_text(string $value, int $maxLength): string
{
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength, 'UTF-8');
    }

    return substr($value, 0, $maxLength);
}

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function clean_header_value(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function show_error(string $message): void
{
    http_response_code(400);
    if (request_wants_json()) {
        header('Content-Type: application/json; charset=UTF-8', true);
        echo json_encode(['success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
        exit;
    }
    header('Content-Type: text/html; charset=UTF-8', true);
    $safeMessage = escape_html($message);
    echo '<!doctype html><html lang="es-AR"><meta charset="utf-8"><title>Consulta no enviada | Conde Graphics</title><body style="font-family:Arial,sans-serif;max-width:42rem;margin:4rem auto;padding:1.5rem;color:#111820"><h1>No pudimos enviar la consulta</h1><p>' . $safeMessage . '</p><p><a href="' . escape_html(SITE_URL . '#contacto') . '">Volver al formulario</a> o escribir a <a href="mailto:' . escape_html(RECIPIENT_EMAIL) . '">' . escape_html(RECIPIENT_EMAIL) . '</a>.</p></body></html>';
    exit;
}

function request_wants_json(): bool
{
    $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
    $requestedWith = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
    return str_contains($accept, 'application/json') || $requestedWith === 'xmlhttprequest';
}

function show_success(): void
{
    header('Content-Type: application/json; charset=UTF-8', true);
    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Valida el token de Turnstile exclusivamente desde el servidor.
 * Nunca se registra el token ni la clave secreta en los logs.
 */
function validate_turnstile(string $token, string $secret): array
{
    if ($token === '' || $secret === '' || !function_exists('curl_init')) {
        return ['success' => false, 'error-codes' => ['invalid-configuration']];
    }

    $curl = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => $secret,
            'response' => $token,
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false || $curlError !== '' || $httpCode < 200 || $httpCode >= 300) {
        return ['success' => false, 'error-codes' => ['siteverify-request-failed']];
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : ['success' => false, 'error-codes' => ['invalid-siteverify-response']];
}

// Rechaza envíos automatizados que completan el campo invisible.
if (post_value('botcheck') !== '') {
    show_error('No pudimos validar la consulta.');
}

// Acepta el formulario desde la versión publicada y desde GitHub Pages.
$origin = clean_header_value($_SERVER['HTTP_ORIGIN'] ?? '');
$allowedOrigins = [
    'https://www.condegraphics.com',
    'https://condegraphics.com',
    'https://condegraphics.github.io',
];
if ($origin !== '' && !in_array($origin, $allowedOrigins, true)) {
    http_response_code(403);
    exit('Origen no autorizado.');
}

$turnstileConfigPath = __DIR__ . '/contact-config.php';
$turnstileConfig = is_file($turnstileConfigPath) ? require $turnstileConfigPath : [];
$turnstileSecret = is_array($turnstileConfig) ? (string) ($turnstileConfig['turnstile_secret'] ?? '') : '';
$turnstileToken = post_value('cf-turnstile-response');
$turnstileResult = validate_turnstile($turnstileToken, $turnstileSecret);
$allowedTurnstileActions = ['contact'];
$allowedTurnstileHostnames = [
    'www.condegraphics.com',
    'condegraphics.com',
    'condegraphics.github.io',
];
$turnstileAction = is_string($turnstileResult['action'] ?? null) ? $turnstileResult['action'] : '';
$turnstileHostname = is_string($turnstileResult['hostname'] ?? null) ? $turnstileResult['hostname'] : '';

if (($turnstileResult['success'] ?? false) !== true
    || !in_array($turnstileAction, $allowedTurnstileActions, true)
    || !in_array($turnstileHostname, $allowedTurnstileHostnames, true)) {
    $errorCodes = $turnstileResult['error-codes'] ?? ['verification-failed'];
    error_log('Conde Graphics contact form: Turnstile validation failed: ' . json_encode($errorCodes, JSON_UNESCAPED_SLASHES));
    show_error('No pudimos validar la verificación de seguridad. Volvé a intentarlo.');
}

$name = limit_text(post_value('name'), MAX_NAME_LENGTH);
$email = limit_text(post_value('email'), MAX_EMAIL_LENGTH);
$phone = limit_text(post_value('phone'), MAX_PHONE_LENGTH);
$website = limit_text(post_value('website'), MAX_WEBSITE_LENGTH);
$budget = limit_text(post_value('budget'), 80);
$message = limit_text(post_value('message'), MAX_MESSAGE_LENGTH);

if ($name === '' || $email === '' || $phone === '' || $budget === '' || $message === '') {
    show_error('Completá todos los campos obligatorios del formulario.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    show_error('Ingresá un email válido para poder responderte.');
}

$allowedBudgets = [
    'Menos de 600 USD',
    'Entre 600 y 1.200 USD',
    'Entre 1.200 y 2.000 USD',
    'Entre 2.000 y 5.000 USD',
    'Más de 5.000 USD',
];
if (!in_array($budget, $allowedBudgets, true)) {
    show_error('Seleccioná una opción válida de presupuesto.');
}

$subjectText = 'Consulta Online | Conde Graphics AI-FIRST SEO Agency Argentina';
$replyToEmail = clean_header_value($email);

$safeName = escape_html($name);
$safeEmail = escape_html($email);
$safePhone = escape_html($phone);
$safeWebsite = escape_html($website !== '' ? $website : 'No informado');
$safeBudget = escape_html($budget);
$safeMessage = nl2br(escape_html($message), false);

$plainText = "Nueva consulta Web - Conde Graphics\n\n";
$plainText .= "Nombre: {$name}\n";
$plainText .= "Email: {$email}\n";
$plainText .= "Teléfono: {$phone}\n";
$plainText .= "Página web: " . ($website !== '' ? $website : 'No informado') . "\n";
$plainText .= "Presupuesto actual: {$budget}\n\n";
$plainText .= "Mensaje del cliente:\n{$message}\n\n";
$plainText .= "Este correo fue enviado desde el formulario de contacto de " . SITE_URL . "\n";

$html = '<!doctype html>'
    . '<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">'
    . '<title>Nueva consulta Web - Conde Graphics</title></head>'
    . '<body style="margin:0;padding:0;background-color:#f4f8fc;color:#111820;font-family:Arial,Helvetica,sans-serif;">'
    . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:#f4f8fc;">'
    . '<tr><td align="center" style="padding:28px 12px;">'
    . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;max-width:720px;background-color:#ffffff;border:1px solid #d9e0e7;">'
    . '<tr><td style="padding:24px 32px;background-color:#00215e;color:#ffffff;">'
    . '<div style="font-size:12px;line-height:18px;letter-spacing:2px;color:#8dcaef;text-transform:uppercase;">CONDE GRAPHICS · FORMULARIO WEB</div>'
    . '<h1 style="margin:12px 0 0;font-size:25px;line-height:32px;font-weight:700;color:#ffffff;">Nueva consulta Web</h1>'
    . '</td></tr>'
    . '<tr><td style="padding:28px 32px 12px;font-size:16px;line-height:24px;color:#111820;">Has recibido una nueva consulta a través del sitio web:</td></tr>'
    . '<tr><td style="padding:0 32px 20px;">'
    . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border-collapse:collapse;font-size:14px;line-height:20px;">'
    . '<tr><td width="34%" style="padding:13px 8px;border-bottom:1px solid #d9e0e7;font-weight:700;color:#53565a;">Nombre:</td><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;">' . $safeName . '</td></tr>'
    . '<tr><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;font-weight:700;color:#53565a;">Email:</td><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;"><a href="mailto:' . $safeEmail . '" style="color:#0073cb;">' . $safeEmail . '</a></td></tr>'
    . '<tr><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;font-weight:700;color:#53565a;">Teléfono:</td><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;">' . $safePhone . '</td></tr>'
    . '<tr><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;font-weight:700;color:#53565a;">Página web:</td><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;">' . $safeWebsite . '</td></tr>'
    . '<tr><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;font-weight:700;color:#53565a;">Presupuesto:</td><td style="padding:13px 8px;border-bottom:1px solid #d9e0e7;">' . $safeBudget . '</td></tr>'
    . '</table>'
    . '</td></tr>'
    . '<tr><td style="padding:0 32px 10px;font-size:14px;line-height:20px;font-weight:700;color:#53565a;">Mensaje del cliente:</td></tr>'
    . '<tr><td style="padding:0 32px 28px;"><div style="padding:18px 20px;background-color:#eaf4fc;border-left:4px solid #0073cb;font-size:15px;line-height:24px;color:#111820;">' . $safeMessage . '</div></td></tr>'
    . '<tr><td style="padding:18px 32px;background-color:#f4f8fc;border-top:1px solid #d9e0e7;font-size:12px;line-height:18px;color:#53565a;">Este correo fue enviado desde el formulario de contacto de <a href="' . escape_html(SITE_URL) . '" style="color:#0073cb;">condegraphics.com</a>.</td></tr>'
    . '</table></td></tr></table></body></html>';

require_once __DIR__ . '/lib/PHPMailer/Exception.php';
require_once __DIR__ . '/lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/lib/PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.gmail.com';
    $mail->SMTPAuth = false;
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->CharSet = 'UTF-8';
    $mail->Timeout = 20;

    $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
    $mail->addAddress(RECIPIENT_EMAIL, SENDER_NAME);
    $mail->addReplyTo($email, $name);
    $mail->Subject = $subjectText;
    $mail->isHTML(true);
    $mail->Body = $html;
    $mail->AltBody = $plainText;
    $mail->send();
} catch (Exception $exception) {
    error_log('Conde Graphics contact form: SMTP send failed: ' . $mail->ErrorInfo);
    show_error('El servidor no pudo entregar el correo en este momento. Probá nuevamente o escribinos por email.');
}

show_success();
