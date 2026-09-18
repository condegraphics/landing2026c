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

- **Titulares:** Raleway, pesos 500–900.
- **Textos:** Roboto, pesos 400–700.
- **Escala:** `clamp()` para titulares, cuerpo base de 16px y lectura móvil prioritaria.

## Composición

El hero usa una composición dividida con texto a la izquierda y la imagen `img/11062.jpg` a la derecha. La sección de servicios trabaja tres columnas en escritorio y una columna en móvil. La sección AI usa `img/54251.jpg` como fondo full width con overlay azul. Se evitan tres o más zigzags consecutivos y no se convierten todos los textos en tarjetas.

## Movimiento

Se utiliza `animation-timeline: view()` para revelar bloques durante el desplazamiento cuando el navegador lo soporta. Se incluye fallback con animación temporal y una ruta completa para `prefers-reduced-motion: reduce`. Ningún contenido esencial depende de la animación.

## Activos

- `img/logo-conde-graphics-agencia-seo-argentina-260px.png`: logo oficial disponible.
- `img/11062.jpg`: tecla AI, hero.
- `img/116836.jpg`: Google, beneficios SEO.
- `img/54251.jpg`: círculo tecnológico, sección AI.
- `img/50332.jpg`: reunión y datos, método.
- `img/15341.jpg`: trabajo Web.
- `img/3066.jpg`: colaboración y proyecto.
- `img/9239.jpg`: equipo y agencia.

El usuario confirmó que los JPG pueden utilizarse comercialmente con libertad.

## Reglas de copy

No se prometen posiciones, menciones, citas, ROI ni resultados garantizados. Se comunican condiciones de visibilidad, claridad, autoridad, experiencia, campañas, conversiones y medición responsable.

## Tracking

GA4, Google Ads, Google Tag Manager y Meta quedan documentados como integración pendiente. No se incluyen scripts activos en esta versión porque el usuario indicó no agregar ni activar cookies o consentimiento.
