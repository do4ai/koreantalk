import fitz
import json
import sys

# Ensure UTF-8 output
sys.stdout.reconfigure(encoding='utf-8')

pdf_path = r'e:\code_e\koreantalk\datas_web\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf'
doc = fitz.open(pdf_path)

# Print pages 11, 12, 13 (which is index 10, 11, 12)
for i in [10, 11, 12]:
    page = doc[i]
    blocks = page.get_text('blocks')
    blocks.sort(key=lambda b: (b[1], b[0]))
    texts = [b[4].strip() for b in blocks if b[4].strip()]
    lines = page.get_text("text").split('\n')
    print(f"=== PAGE {i+1} ===")
    print("HEIGHT:", page.rect.height)
    for b in blocks:
        if b[4].strip():
            print(f"[{b[1]:.2f}] {b[4].strip()!r}")
