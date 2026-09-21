# Configuración del formulario propio

## Estado

El código del formulario está preparado para usar **PHPMailer con SMTP Relay de Google Workspace**. La regla del relay autoriza la IP de Hostinger y no requiere usuario, contraseña de aplicación ni archivo privado adicional. Falta publicar este ajuste y ejecutar una prueba real de entrega.

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

## Paso 1: regla SMTP Relay ya configurada

En Google Admin quedó creada y habilitada la regla `Formulario Web Conde Graphics` con estas opciones:

- Remitentes: sólo las direcciones del dominio.
- Autenticación: sólo aceptar correo desde la IP especificada de Hostinger.
- Autenticación SMTP: desactivada.
- Cifrado TLS: activado.
- Servidor que utilizará el código: `smtp-relay.gmail.com:587`.

Los cambios de Google pueden tardar algunos minutos y, en algunos casos, hasta 24 horas.

## Paso 2: configuración en Hostinger

No hay que crear `contact-config.php` ni cargar contraseñas. El endpoint contiene únicamente los valores públicos del relay: servidor, puerto, TLS y autenticación desactivada. Si Hostinger sincroniza el repositorio, no debe agregarse ningún archivo privado adicional.

## Paso 3: prueba controlada

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
