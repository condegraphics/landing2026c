# Configuración del formulario propio

## Estado

El código del formulario está preparado para usar **PHPMailer con SMTP autenticado**. La publicación del código no incluye credenciales. Falta completar el archivo privado `contact-config.php` en Hostinger y ejecutar una prueba real de entrega.

## Por qué hace falta esta configuración

La página HTML sólo recopila los datos. El envío se ejecuta en `contact.php`, dentro de Hostinger. La función PHP `mail()` aceptaba la solicitud, pero no garantizaba que Google Workspace aceptara o entregara el mensaje. PHPMailer conecta directamente con un servidor SMTP autenticado.

El dominio tiene sus registros MX en Google Workspace. Por eso, la configuración recomendada es utilizar la cuenta remitente `info@condegraphics.com` mediante el servidor SMTP de Google:

| Parámetro | Valor |
|---|---|
| Servidor | `smtp.gmail.com` |
| Puerto | `587` |
| Seguridad | STARTTLS |
| Usuario | `info@condegraphics.com` |
| Contraseña | Contraseña de aplicación de Google, nunca la contraseña normal |
| Destinatario | `info@condegraphics.com` |

Google también ofrece SMTP Relay (`smtp-relay.gmail.com`) para administradores de Workspace. Es una alternativa más avanzada, basada en autenticación por IP u OAuth2, y requiere configuración en la consola de administración.

## Paso 1: crear la contraseña de aplicación

1. Ingresar a la cuenta de Google Workspace de `info@condegraphics.com`.
2. Verificar que esté activa la **Verificación en dos pasos**.
3. Abrir la sección oficial de [Contraseñas de aplicación de Google](https://support.google.com/accounts/answer/185833).
4. Crear una contraseña de aplicación para una aplicación identificada como `Conde Graphics Web`.
5. Guardar la contraseña de aplicación de 16 caracteres en un administrador de contraseñas. No enviarla por chat, email ni GitHub.

Si Google no muestra la opción, la cuenta puede estar administrada con una política que la bloquea, tener Protección avanzada o usar una modalidad de verificación incompatible. En ese caso, un administrador de Google Workspace debe configurar SMTP Relay u OAuth2.

## Paso 2: preparar el archivo privado en Hostinger

Después de que el código se encuentre desplegado en Hostinger:

1. Abrir **Hostinger → Websites → Administrar → File Manager**.
2. Entrar en la carpeta pública del dominio, normalmente `public_html`.
3. Crear un archivo llamado exactamente `contact-config.php`.
4. Copiar la estructura siguiente y reemplazar sólo el valor de `smtp_password` con la contraseña de aplicación. No copiar la contraseña en el repositorio ni en este documento.

```php
<?php

return [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_secure' => 'tls',
    'smtp_username' => 'info@condegraphics.com',
    'smtp_password' => 'PEGAR_AQUI_LA_CONTRASEÑA_DE_APLICACION',
];
```

5. Guardar el archivo en la misma carpeta donde se encuentra `contact.php`.
6. Si Hostinger permite modificar permisos, usar permisos restrictivos para que sólo el usuario del sitio pueda leerlo, normalmente `600` o el nivel privado recomendado por Hostinger.
7. No abrir el archivo desde el navegador para mostrarlo ni compartir una captura que incluya la contraseña. El `.htaccess` del proyecto bloquea el acceso web directo a `contact-config.php`.

El archivo está excluido mediante `.gitignore`. Si una sincronización Git de Hostinger elimina archivos no versionados, volver a cargar `contact-config.php` después de cada despliegue o usar las variables de entorno privadas de Hostinger si están disponibles en el plan.

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

Las credenciales no se guardan en GitHub. El código utiliza PHPMailer 6.9.3, distribuido bajo LGPL-2.1, y conserva su archivo de licencia en `lib/PHPMailer/LICENSE`. La contraseña de aplicación puede revocarse desde Google sin modificar el formulario. No se debe activar el modo de depuración SMTP en producción porque podría exponer información del servidor.

El formulario conserva la confirmación `gracias.html`, el honeypot, la validación del navegador y la validación server-side. El correo se genera como HTML con estilos inline y también incluye una parte de texto plano para clientes de correo que no renderizan HTML.
