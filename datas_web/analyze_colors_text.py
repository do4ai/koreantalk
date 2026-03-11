
import fitz

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"
doc = fitz.open(pdf_path)
page = doc[11] # Page 12

print("=== Text Color Analysis ===")
# 'dict' output gives spans with font size, color, etc.
blocks = page.get_text("dict")["blocks"]

for b in blocks:
    if "lines" not in b: continue
    for line in b["lines"]:
        for span in line["spans"]:
            text = span["text"].strip()
            color = span["color"] # integer sRGB
            
            # Convert integer color to hex or RGB to check for red
            # sRGB integer is usually generic. Let's see what PyMuPDF returns.
            # actually it returns an int.
            
            # If color is NOT black (0), print it
            if color != 0:
                # Convert to hex for readability
                b_col = color & 255
                g_col = (color >> 8) & 255
                r_col = (color >> 16) & 255
                
                # Check for redness: R high, G/B low
                # Note: fitz span['color'] space depends on flags but usually sRGB
                print(f"Text: '{text}' | Color: {color} (Hex: {color:06x}) | Pos: {span['bbox']}")
                
print("\n=== All Text at Bottom ===")
# Checking text at bottom of page
height = page.rect.height
footer_threshold = height - 100
for b in blocks:
    if b["bbox"][1] > footer_threshold:
        for line in b["lines"]:
            for span in line["spans"]:
                print(f"Bottom Text: '{span['text']}' bbox={span['bbox']}")
