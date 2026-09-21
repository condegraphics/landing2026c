# Configuración del formulario propio

## Estado

El código del formulario usa **Cloudflare Turnstile + PHPMailer con SMTP Relay de Google Workspace**. Turnstile funciona en modo Managed con apariencia `interaction-only`: el visitante normal no ve un paso adicional; sólo una interacción sospechosa puede mostrar una verificación. La regla SMTP autoriza la IP de Hostinger y no requiere usuario ni contraseña de aplicación. Falta cargar la Secret Key privada en Hostinger y ejecutar una prueba controlada.

## Por qué hace falta esta configuración

La página HTML sólo recopila los datos. El envío se ejecuta en `contact.php`, dentro de Hostinger. La función PHP `mail()` aceptaba la solicitud, pero no garantizaba que Google Workspace aceptara o entregara el mensaje. PHPMailer conecta directamente con el SMTP Relay de Google mediante TLS y la autorización por IP configurada en Google Admin.

El dominio tiene sus registros MX en Google Workspace. Por eso, la configuración recomendada es utilizar la cuenta remitente `info@condegraphics.com` mediante el servidor SMTP de Google:

| Parámetro | Valor |
|---|---|
| Servidor | `smtp-relay.gmail.com` |
| Puerto | `587` |
| Seguridad | STARTTLS |
| Usuario | No se utiliza |
| Contraseña | No se utiliza |
| Destinatario | `info@condegraphics.com` |

Google ofrece SMTP Relay (`smtp-relay.gmail.com`) para administradores de Workspace. En este proyecto se utiliza la autorización por IP, con TLS obligatorio, configurada en la regla `Formulario Web Conde Graphics`.

## Paso 1: configurar Turnstile

El widget `Conde Graphics Contact Form` fue creado en la cuenta de Cloudflare que administra `condegraphics.com`, con estos hostnames:

- `condegraphics.com`
- `www.condegraphics.com`
- `condegraphics.github.io`

La Site Key pública está incluida en `index.html`. La Secret Key nunca se guarda en GitHub.

En Hostinger, dentro de la misma carpeta que `contact.php`, crear el archivo privado `contact-config.php` con esta estructura:

```php
<?php

return [
    'turnstile_secret' => 'PEGAR_AQUI_LA_SECRET_KEY_PRIVADA',
];
```

No incluir comillas adicionales ni espacios dentro de la clave. No enviar la clave por chat. El archivo está excluido mediante `.gitignore` y bloqueado para acceso web directo mediante `.htaccess`.

El endpoint valida el token con `https://challenges.cloudflare.com/turnstile/v0/siteverify` antes de preparar o enviar el email. Comprueba `success`, la acción `contact` y el hostname autorizado. Si la validación falla, no se envía ningún correo.

## Paso 2: regla SMTP Relay ya configurada

En Google Admin quedó creada y habilitada la regla `Formulario Web Conde Graphics` con estas opciones:

- Remitentes: sólo las direcciones del dominio.
- Autenticación: sólo aceptar correo desde la IP especificada de Hostinger.
- Autenticación SMTP: desactivada.
- Cifrado TLS: activado.
- Servidor que utilizará el código: `smtp-relay.gmail.com:587`.

Los cambios de Google pueden tardar algunos minutos y, en algunos casos, hasta 24 horas.

## Paso 3: configuración SMTP en Hostinger

El endpoint contiene los valores públicos del relay: servidor, puerto, TLS y autenticación desactivada. Además, necesita el archivo privado `contact-config.php` únicamente para la Secret Key de Turnstile. Si Hostinger sincroniza el repositorio, verificar que el archivo no versionado permanezca en la carpeta pública después de cada despliegue.

## Paso 4: prueba controlada

1. Abrir `https://www.condegraphics.com/`.
2. Completar el formulario con datos de prueba, no con un lead real.
3. Enviar una sola vez.
4. Verificar que el navegador llegue a `https://www.condegraphics.com/gracias.html`.
5. Revisar `info@condegraphics.com` en Recibidos, Spam y Promociones.
6. Confirmar que el correo muestre una tabla HTML con nombre, email, teléfono, página web, presupuesto y mensaje.
7. Responder el email recibido y verificar que el botón o la acción de respuesta utilice el `Reply-To` del potencial cliente.

Si no llega el mensaje, revisar el log de errores PHP de Hostinger. El endpoint registra un error técnico genérico para el administrador, pero nunca muestra la contraseña ni el detalle SMTP al visitante.

## Seguridad y mantenimiento

No se guardan credenciales en GitHub ni en Hostinger. El código utiliza PHPMailer 6.9.3, distribuido bajo LGPL-2.1, y conserva su archivo de licencia en `lib/PHPMailer/LICENSE`. La seguridad depende de la regla de Google Admin, la IP autorizada y TLS. No se debe activar el modo de depuración SMTP en producción porque podría exponer información del servidor.

El formulario conserva la confirmación `gracias.html`, el honeypot, la validación del navegador y la validación server-side. El correo se genera como HTML con estilos inline y también incluye una parte de texto plano para clientes de correo que no renderizan HTML.
