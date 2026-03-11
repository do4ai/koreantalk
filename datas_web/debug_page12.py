
import fitz
import re

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"

doc = fitz.open(pdf_path)
page = doc[11] # Page 12 (0-indexed 11)

blocks = page.get_text("blocks")
blocks.sort(key=lambda b: (b[1], b[0])) # Sort by Y, then X

print(f"--- Page 12 Text Blocks ---")
full_text = ""
for b in blocks:
    text = b[4]
    print(f"[Block] {text.strip()}")
    full_text += text + "\n"

print(f"--- Full Text Analysis ---")
# Try the extraction logic on this text
q_pattern = re.compile(r'^\s*(\d{1,3})\.\s*(.*)')

lines = full_text.split('\n')
current_q = None
data = []

for line in lines:
    line = line.strip()
    if not line: continue
    
    q_match = q_pattern.match(line)
    if q_match:
        if current_q: data.append(current_q)
        current_q = {"id": q_match.group(1), "raw_context": ""}
        print(f"Found Question: {line}")
    else:
        if current_q:
            current_q["raw_context"] += line + "\n"
if current_q: data.append(current_q)

# Now check choices for each
for item in data:
    print(f"\nProcessing Q {item['id']} context len: {len(item['raw_context'])}")
    text = item['raw_context']
    
    # Dump context with indices of symbols
    symbols = ['①', '②', '③', '④', '⑤']
    for s in symbols:
        idx = text.find(s)
        while idx != -1:
            print(f"  Found {s} at {idx}")
            idx = text.find(s, idx+1)
