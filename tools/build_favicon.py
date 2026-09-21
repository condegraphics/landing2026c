from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[1]
source = ROOT / "img" / "logo-conde-graphics-seo-agency.svg"
out = ROOT / "img" / "favicon"
out.mkdir(parents=True, exist_ok=True)

svg = source.read_text(encoding="utf-8")
paths = []
for match in re.finditer(r'<path\s+class="(fil[023])"\s+d="([^"]+)"\s*/>', svg):
    css_class, data = match.groups()
    fill = {"fil0": "#4185F4", "fil2": "#1958BF", "fil3": "#FFFFFF"}[css_class]
    paths.append(f'  <path fill="{fill}" d="{data}"/>')

if len(paths) != 6:
    raise SystemExit(f"Se esperaban 6 formas del isotipo; se encontraron {len(paths)}")

favicon = """<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10000 10000" role="img" aria-labelledby="title desc">
  <title id="title">Conde Graphics</title>
  <desc id="desc">Isotipo circular azul de Conde Graphics</desc>
%s
</svg>
""" % "\n".join(paths)
(out / "favicon.svg").write_text(favicon, encoding="utf-8")

manifest = """{
  "name": "Conde Graphics",
  "short_name": "Conde Graphics",
  "start_url": "/",
  "display": "standalone",
  "theme_color": "#00215E",
  "background_color": "#00215E",
  "icons": [
    {
      "src": "favicon.svg",
      "sizes": "any",
      "type": "image/svg+xml",
      "purpose": "any maskable"
    }
  ]
}
"""
(out / "site.webmanifest").write_text(manifest, encoding="utf-8")
print(f"Generados: {out / 'favicon.svg'} y {out / 'site.webmanifest'}")
print(f"Formas extraídas del logo oficial: {len(paths)}")
