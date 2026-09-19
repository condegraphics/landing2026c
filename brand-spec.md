# Conde Graphics — Brand spec de la OnePage

## Design Read

- **Artifact:** OnePage HOME institucional y comercial.
- **Audience:** pymes, empresas, profesionales, comercios, negocios B2B, industrias, fábricas y marcas.
- **Visual language:** editorial de agencia + tecnología aplicada, profesional, blanco predominante y contraste fotográfico.
- **Mode:** greenfield sobre repositorio con activos de marca ya versionados.
- **Visual variance:** 6/10. Grilla estable con bloques asimétricos controlados.
- **Motion intensity:** 5/10. Reveal suave y scroll-driven CSS sin JavaScript propio.
- **Information density:** 5/10. Lectura escaneable con contenido suficiente para SEO.
- **Asset dependence:** 7/10. Logo e imágenes reales son parte central de la identidad.
- **Brand fidelity:** 8/10. Se conserva el logo, el azul institucional y la idea de agencia SEO Argentina.

## Sistema visual

- **Azul día:** `#0073CB` — CTAs, enlaces, numeración y acentos.
- **Azul noche:** `#00215E` — hero, sección AI, contacto y superficies de autoridad.
- **Azul noche 2:** `#001843` — estados hover y profundidad.
- **Azul hielo:** `#EAF4FC` — superficies suaves y estados de interacción.
- **Azul pálido:** `#F4F8FC` — fondos secundarios.
- **Negro:** `#000000` — footer.
- **Gris metal:** `#53565A` — texto secundario.
- **Gris luminoso:** `#BBBBBB` — textos de baja jerarquía.
- **Gris de línea:** `#D9E0E7` — divisores y bordes.
- **Blanco cálido:** `#FCFCFB` — superficies de lectura.

## Tipografía

- **Familia única:** Merriweather Sans, cargada desde Google Fonts.
- **Titulares:** Merriweather Sans, peso 800, aplicado a `h1`, `h2` y `h3`.
- **Demás textos:** Merriweather Sans, peso 300, aplicado a cuerpo, navegación, botones, etiquetas, formularios y textos auxiliares.
- **Escala:** H2 de escritorio en 56px con line-height de 67px; H1 de escritorio en 86px con line-height de 96px; cuerpo base de 16px y lectura móvil prioritaria.

## Composición

El hero utiliza exclusivamente `img/hero-2026-ok.jpg` como fondo full width, con dimensiones verificadas de **1920 × 934 px** y una relación aproximada de **2,056:1**. El CSS respeta esa proporción en escritorio y libera el aspect ratio en tablet y móvil. Se mantiene un overlay azul para asegurar el contraste del texto y no hay una imagen superpuesta independiente. El H1 de escritorio usa **86px / 96px**. La sección de servicios trabaja tres columnas en escritorio y una columna en móvil. La sección AI usa `img/54251.jpg` como fondo full width con overlay azul. Se evitan tres o más zigzags consecutivos y no se convierten todos los textos en tarjetas.

## Movimiento

Se utiliza `animation-timeline: view()` para revelar bloques durante el desplazamiento cuando el navegador lo soporta. Se incluye fallback con animación temporal y una ruta completa para `prefers-reduced-motion: reduce`. Ningún contenido esencial depende de la animación.

## Activos

- `img/logo-conde-graphics-agencia-seo-argentina-260px.png`: logo oficial disponible.
- `img/hero-2026-ok.jpg`: fondo full width de la portada, 1920 × 934 px.
- `img/hero-2026.jpg`: versión anterior del fondo, conservada como activo del repositorio.
- `img/11062.jpg`: tecla AI, disponible como activo secundario.
- `img/116836.jpg`: Google, beneficios SEO.
- `img/54251.jpg`: círculo tecnológico, sección AI.
- `img/50332.jpg`: reunión y datos, método.
- `img/15341.jpg`: trabajo Web.
- `img/3066.jpg`: colaboración y proyecto.
- `img/9239.jpg`: equipo y agencia.

El usuario confirmó que los JPG pueden utilizarse comercialmente con libertad.

## Reglas de copy

No se prometen posiciones, menciones, citas, ROI ni resultados garantizados. Se comunican condiciones de visibilidad, claridad, autoridad, experiencia, campañas, conversiones y medición responsable.

## Énfasis SEO editorial

La landing utiliza énfasis de forma selectiva, no decorativa. Las frases estratégicas de intención —como visibilidad, búsquedas, presencia orgánica, autoridad, conversión y oportunidad— pueden destacarse con azul institucional; los conceptos de servicio o beneficio más relevantes pueden usar `strong` en Merriweather Sans 800. El criterio es reforzar comprensión y escaneabilidad sin colorear cada palabra ni convertir el copy en una lista de keywords.

## Ritmo y aire

Se amplió el espaciado entre eyebrow, títulos, párrafos, listas y CTA. Las secciones principales utilizan paddings verticales más generosos y las grillas tienen gaps mayores en escritorio. En móvil se conservan valores más compactos para evitar desplazamientos innecesarios, sin volver a amontonar los elementos.

## Botones y hero

Todos los botones utilizan `border-radius: 0`. Cuando dos CTA aparecen juntos en el hero, comparten el mismo ancho mediante una distribución flexible; en móvil pasan a ocupar el mismo ancho disponible en columna. El overlay azul del hero se mantiene al 50% de opacidad para preservar más detalle de `hero-2026-ok.jpg`.

En móvil, la escala tipográfica se reduce con un interlineado más compacto: H1 entre 37.6px y 55.2px, H2 entre 28.8px y 42.4px y H3 entre 20.7px y 23.2px, según el ancho disponible. Los dos CTA del hero se ocultan por completo en pantallas de hasta 680px; el acceso “HABLEMOS” del encabezado permanece disponible.

La revisión UX/UI de la composición corrigió tres causas de desalineación: el encabezado de servicios ahora comparte una grilla de tres columnas con las tarjetas; los enlaces de las tarjetas se ubican después del contenido mediante flexbox, sin superponerse a listas; y los principios de SEO para IA usan un contenedor de texto propio para evitar párrafos comprimidos en la columna numérica. El collage Web conserva la proporción natural de sus imágenes y las grillas aplican `min-width: 0` para evitar condensación o desbordes.

## Tracking

GA4, Google Ads, Google Tag Manager y Meta quedan documentados como integración pendiente. No se incluyen scripts activos en esta versión porque el usuario indicó no agregar ni activar cookies o consentimiento.
