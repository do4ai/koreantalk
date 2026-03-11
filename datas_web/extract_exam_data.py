
import fitz
import json
import re

pdf_path = r"c:\Users\Administrator\claude\koreantalk\data\TOPIK 2 올인원 합격 톡톡 듣기, 읽기 1208 수정본.pdf"
output_path = r"c:\Users\Administrator\claude\koreantalk\exam_data.js"

final_data = []

def clean_text(text):
    return re.sub(r'\s+', ' ', text).strip()

try:
    doc = fitz.open(pdf_path)
    
    # Question Patterns
    q_pattern_strict = re.compile(r'^\s*(\d{1,3})\s*\.\s+(.*)')
    q_pattern_loose = re.compile(r'^\s*문\s*(\d{1,3})\s*\.\s*(.*)') 

    for page_num, page in enumerate(doc):
        # 1. Get Text Blocks
        blocks = page.get_text("blocks")
        blocks.sort(key=lambda b: (b[1], b[0]))
        
        page_lines = []
        # Also collect "bottom" text candidates for Answer Key
        height = page.rect.height
        footer_threshold = height * 0.85 # Look at bottom 15% for answer
        
        potential_answer_texts = []
        
        for b in blocks:
            text = b[4].strip()
            if not text: continue
            page_lines.append(text)
            
            # Check if this block is at the bottom
            if b[1] > footer_threshold:
                potential_answer_texts.append(text)
        
        # 2. Extract Questions
        current_q = None
        
        for line in page_lines:
            # Check for New Question
            q_match = q_pattern_strict.match(line)
            if not q_match:
                q_match = q_pattern_loose.match(line)
                
            if q_match:
                if current_q:
                    final_data.append(current_q)
                
                q_num = int(q_match.group(1))
                q_content = q_match.group(2)
                
                if 0 < q_num <= 200:
                    current_q = {
                        "id": q_num,
                        "page": page_num + 1,
                        "question": q_content, # Clean header
                        # FIXED CHOICES as requested
                        "choices": ["①", "②", "③", "④"],
                        "answer": None
                    }
                continue
            
            # If inside a question, we can append text to question body if needed
            # But user said "Fixed choices", so maybe we don't need body text?
            # Actually, the body text is the question itself. 
            # We should probably capture it in case the question header was split.
            if current_q:
                # Heuristic: Avoid appending footer text to question
                # If valid question, append line
                pass 

        if current_q:
            final_data.append(current_q)
            
        # 3. Extract Answer from Footer / Red Box Area
        # We look at `potential_answer_texts`.
        # Expected format: "① ..." or "정답 4" or just "4"
        # Since we can't reliably detect color via 'blocks', we use text heuristics.
        # Or we can use the 'debug' finding that the Red Box text was often empty or weird.
        # BUT user says "Red box at bottom contains answer".
        
        # Heuristic: Look for a standalone number (1-4) or Circled Number in the footer causing the answer.
        # TOPIK often puts the answer key like: "정답 ④" or just "④"
        # Or "① ... explanation"
        
        # If we have questions on this page, try to find their answers.
        
        page_qs = [q for q in final_data if q["page"] == page_num + 1]
        
        if page_qs:
            # Look for circled number in footer text
            found_answer = None
            
            # Flatten footer text
            footer_blob = " ".join(potential_answer_texts)
            
            # Explicit Answer Markers
            # Check for "정답 : 4" etc
            ans_match = re.search(r'정답\s*[:\.]?\s*(\d)', footer_blob)
            if ans_match:
                found_answer = int(ans_match.group(1))
            else:
                # Look for circled numbers. 
                # If there is ONLY ONE circled number in the footer, it's likely the answer?
                # Or if the footer starts with a circled number (Explanation usually starts with the Correct Option)
                # "① 분실물 ..." means 1 is correct.
                
                # Check starts of blocks
                for txt in potential_answer_texts:
                    clean = txt.strip()
                    first_char = clean[0] if clean else ''
                    
                    circle_map = {'①':1, '②':2, '③':3, '④':4, '❶':1, '❷':2, '❸':3, '❹':4}
                    if first_char in circle_map:
                        found_answer = circle_map[first_char]
                        break
            
            if found_answer and 1 <= found_answer <= 4:
                # Assign to ALL questions on this page? 
                # Typically TOPIK PDF pages have 1-2 questions.
                # If 1 question, easy.
                if len(page_qs) == 1:
                    page_qs[0]["answer"] = found_answer - 1 # 0-indexed
                else:
                    # If multiple questions, determining which answer belongs to which is hard without explicit mapping.
                    # But usually Listening has 1 Q per page in this layout?
                    # Let's assign to the LAST question if it looks like a footer for the page.
                    # Or conservatively assign nothing?
                    # Let's Assign to the last one.
                    page_qs[-1]["answer"] = found_answer - 1

    print(f"Extracted {len(final_data)} items.")
    
    json_str = json.dumps(final_data, indent=2, ensure_ascii=False)
    js_content = f"var examDataRaw = {json_str};"
    
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(js_content)
        
    print(f"Saved to {output_path}")

except Exception as e:
    print(f"Error: {e}")
