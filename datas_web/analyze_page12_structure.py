
import fitz

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"
doc = fitz.open(pdf_path)
page = doc[11] # Page 12

print("=== Drawings/Shapes ===")
paths = page.get_drawings()
for p in paths:
    # Check for fill color or stroke color being reddish
    # color is usually a tuple (r, g, b) floats 0-1
    color = p.get("fill")
    stroke = p.get("color")
    
    is_red = False
    if color and len(color) >= 3 and color[0] > 0.8 and color[1] < 0.2 and color[2] < 0.2:
        is_red = True
    if stroke and len(stroke) >= 3 and stroke[0] > 0.8 and stroke[1] < 0.2 and stroke[2] < 0.2:
        is_red = True
        
    if is_red:
        rect = p["rect"]
        print(f"Red Shape at {rect}, items: {p.get('items')}")
        # Check text inside this rect
        text_in_rect = page.get_text("text", clip=rect)
        print(f"  -> Text inside red content: '{text_in_rect.strip()}'")

print("\n=== Text Blocks ===")
blocks = page.get_text("blocks")
blocks.sort(key=lambda b: (b[1], b[0]))
for b in blocks:
    print(f"Y={b[1]:.1f} | {b[4].strip()[:50].replace('\n', ' ')}")
