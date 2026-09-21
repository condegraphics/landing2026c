from pathlib import Path
from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
src = ROOT / "img" / "hero-open.jpg"
dst = ROOT / "img" / "og-conde-graphics.jpg"

with Image.open(src) as image:
    image = image.convert("RGB")
    target_ratio = 1200 / 630
    crop_width = round(image.height * target_ratio)
    left = max(0, min(image.width - crop_width, 70))
    top = 0
    cropped = image.crop((left, top, left + crop_width, image.height))
    output = cropped.resize((1200, 630), Image.Resampling.LANCZOS)
    output.save(dst, format="JPEG", quality=86, optimize=True, progressive=True)

print(f"Generada {dst} ({output.width}x{output.height})")
