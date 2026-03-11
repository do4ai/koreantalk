
import fitz

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"
doc = fitz.open(pdf_path)
page = doc[11] # Page 12

print("=== Red Text Analysis ===")
d = page.get_text("dict")
for b in d["blocks"]:
    if "lines" not in b: continue
    for line in b["lines"]:
        for span in line["spans"]:
            color = span["color"]
            # Check for pure red or close to it
            r = (color >> 16) & 255
            g = (color >> 8) & 255
            b_val = color & 255
            
            if r > 200 and g < 50 and b_val < 50:
                print(f"Red Text Found: '{span['text']}' at {span['bbox']}")

print("\n=== Red Drawing Analysis ===")
drawings = page.get_drawings()
for p in drawings:
    fill = p.get("fill")
    if fill and len(fill) == 3:
        if fill[0] > 0.8 and fill[1] < 0.2 and fill[2] < 0.2:
            rect = p["rect"]
            print(f"Red Box Found at {rect}")
            # Get text inside this rect
            text_in_rect = page.get_text("text", clip=rect)
            print(f"  -> Content: '{text_in_rect.strip()}'")
