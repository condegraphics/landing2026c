# Acceptance notes — Conde Graphics landing

**URL pública:** https://condegraphics.github.io/landing2026c/  
**Repositorio público:** https://github.com/condegraphics/landing2026c  
**Rama publicada:** `landing-onepage`  
**Fecha:** 2026-09-18  
**Estado:** publicación y revisión pública completadas.

## Acceptance scope

Se revisó la Home pública como usuario final mediante navegación y vista de navegador. También se comprobó por HTTP la página, la hoja CSS, `robots.txt`, `sitemap.xml`, el logo y la imagen principal.

## Pasó

La URL pública respondió con HTTP 200. El título público es `SEO, SEM y Web para hacer visible tu negocio | Conde Graphics`. La Home mostró correctamente un único H1, navegación principal, CTA de WhatsApp, enlace a servicios, servicios SEO/SEM/Web, bloque de beneficios, enfoque SEO en la era de la IA, método, Web, agencia, FAQ, contacto y footer.

La revisión visual confirmó la composición dividida del hero, el predominio de azul noche, la lectura blanca sobre fondo oscuro, el contraste con la imagen AI, la sección central clara y el cierre con contacto, formulario y footer. La página tiene fallback visual para animaciones CSS y una ruta `prefers-reduced-motion`.

Los siguientes recursos respondieron HTTP 200: `/`, `/styles.css`, `/robots.txt`, `/sitemap.xml`, `/img/11062.jpg` y `/img/logo-conde-graphics-agencia-seo-argentina-260px.png`.

Los destinos principales están preparados para WhatsApp, email, llamada y anclas internas. El formulario usa `mailto:info@condegraphics.com` y muestra explícitamente que abrirá el cliente de correo del visitante.

## Tipografía

La versión actual utiliza Merriweather Sans como familia única. Los títulos `h1`, `h2` y `h3` usan peso 800; el resto de la interfaz usa peso 300.

## Medición

No se cargan scripts activos de GA4, Google Ads, Google Tag Manager ni Meta, de acuerdo con la restricción del usuario de no agregar o activar cookies/consentimiento en esta primera versión.

## Limitaciones de la aceptación

La herramienta de navegador disponible no permitió ejecutar la acción de consola en esta sesión. Por eso la comprobación de JavaScript de consola no se declara como pasada. La implementación no contiene JavaScript funcional propio ni dependencias runtime; el comportamiento principal se basa en HTML semántico y CSS.

La revisión responsive se cubrió mediante reglas CSS explícitas para 900px y 680px y el análisis visual de la vista pública disponible. No se generaron capturas automatizadas separadas para cada viewport.

## Publicación

GitHub Pages se habilitó desde la configuración autenticada del repositorio, con `landing-onepage` como rama fuente y `/` como raíz. El workflow auxiliar que había fallado por permisos de integración fue retirado después de que la publicación por rama quedó operativa.
