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

La actualización posterior reemplazó el fondo del hero por `img/hero-2026-ok.jpg`, verificada en 1920 × 934 px. El hero respeta esa proporción en escritorio, libera el alto proporcional en tablet/móvil y usa un H1 de 86px con line-height de 96px en escritorio. Se conserva un overlay azul para la legibilidad del texto.

La versión actual incorpora énfasis editorial SEO selectivo: azul institucional para frases de intención y `strong` para pocos conceptos de servicio, beneficio y conversión. Se evitó resaltar bloques completos o repetir el recurso en cada línea.

La revisión visual posterior redujo todos los H2 de escritorio a 56px con line-height de 67px, retiró el marcador `01` de la introducción, convirtió “Analizar mi proyecto” en un botón outline azul sin relleno y aumentó el aire entre etiquetas, títulos, párrafos, grillas y CTAs.

La actualización siguiente dejó todos los botones con `border-radius: 0`, igualó el ancho de los dos CTA del hero y redujo la opacidad del overlay azul del hero al 50% para recuperar presencia de la imagen de fondo.

La revisión móvil posterior redujo H1, H2 y H3, ajustó sus interlineados a valores más compactos y ocultó los dos CTA del hero hasta 680px de ancho. El botón “HABLEMOS” del encabezado no se oculta.

La revisión UX/UI de escritorio y responsive corrigió la alineación del encabezado de servicios con la tercera tarjeta, evitó la superposición de enlaces sobre listas, abrió correctamente los textos de los principios AI y mantuvo las imágenes del collage Web en su proporción natural. También se añadieron protecciones de grilla para prevenir columnas comprimidas y overflow.

La revisión de CTA convirtió los enlaces accionables de introducción, servicios y Web en botones outline sin relleno. Se unificaron las variantes para fondos claros y oscuros, con borde recto, hover/focus de inversión de contraste y desplazamiento sutil de la flecha. Los CTA primarios rellenos permanecen diferenciados.

En la grilla de servicios se extendió la altura de las tarjetas para separar el contenido de los botones, se anclaron los tres CTA a la misma línea inferior, se igualó su ancho, se centraron los textos y se eliminaron las flechas exclusivamente de estos tres botones.

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
