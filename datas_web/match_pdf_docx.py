import os
import json
import pdfplumber
from docx import Document
import re
from difflib import SequenceMatcher

def clean_pdf_text(text):
    # Koreantalk.net 삭제
    text = text.replace("Koreantalk.net", "")
    
    lines = []
    for line in text.split('\n'):
        line = line.strip()
        if not line: continue
        
        # 1. 숫자와 공백으로만 된 줄 (긴 ID나 페이지 번호 노이즈) 제거
        if re.match(r'^[\d\s]+$', line):
            # 숫자가 3자 이상이면 노이즈로 간주하고 제거
            if len(re.sub(r'\s+', '', line)) >= 3:
                continue
        
        lines.append(line)
    
    return "\n".join(lines).strip()

def normalize(text):
    return re.sub(r'[\s\W_]+', '', text)

def get_ratio(a, b):
    if not a or not b: return 0
    return SequenceMatcher(None, a, b).ratio()

def extract_pdf_data(pdf_path):
    print(f"📄 PDF 분석 중: {pdf_path}")
    pages = []
    with pdfplumber.open(pdf_path) as pdf:
        for i, page in enumerate(pdf.pages):
            raw_text = page.extract_text() or ""
            cleaned = clean_pdf_text(raw_text)
            lines = [l.strip() for l in cleaned.split('\n') if len(normalize(l)) > 3]
            
            pages.append({
                "page": i + 1,
                "text": cleaned,
                "first_anchor": normalize(lines[0]) if lines else "",
                "last_anchor": normalize(lines[-1]) if lines else "",
                "full_norm": normalize(cleaned)
            })
    return pages

def extract_docx_data(docx_path):
    print(f"📘 DOCX 분석 중: {docx_path}")
    doc = Document(docx_path)
    paras = []
    for i, p in enumerate(doc.paragraphs):
        text = p.text.strip()
        if text:
            paras.append({
                "idx": i,
                "text": text,
                "norm": normalize(text)
            })
    return paras

def find_best_match(anchor, paras, search_range=None):
    if not anchor: return None
    best_idx = None
    max_score = 0
    
    start_idx = search_range[0] if search_range else 0
    end_idx = search_range[1] if search_range else len(paras)
    
    for i in range(start_idx, end_idx):
        # 앵커가 문단에 포함되어 있거나, 문단이 앵커 포함
        if anchor[:15] in paras[i]["norm"] or paras[i]["norm"][:15] in anchor:
            return i
        
        # 유사도 체크
        score = get_ratio(anchor[:20], paras[i]["norm"][:20])
        if score > max_score:
            max_score = score
            best_idx = i
            
    return best_idx if max_score > 0.6 else None

def match_logic(pdf_pages, docx_paras):
    print("🔄 페이지별 앵커 기반 매칭 시작...")
    results = []
    
    last_docx_ptr = 0
    
    for i, pdf in enumerate(pdf_pages):
        pdf_text = pdf["text"]
        if not pdf_text:
            results.append({"page": pdf["page"], "pdf_text": "", "matched_text": ""})
            continue

        # 1. 페이지 시작점 찾기 (현재 위치부터 200문단 내 우선 검색)
        start_idx = find_best_match(pdf["first_anchor"], docx_paras, (last_docx_ptr, min(last_docx_ptr + 200, len(docx_paras))))
        if start_idx is None:
            # 전역 검색
            start_idx = find_best_match(pdf["first_anchor"], docx_paras)

        # 2. 페이지 끝점 찾기
        # 다음 페이지의 시작점을 끝점으로 사용 (가장 정확한 경계)
        end_idx = None
        if i + 1 < len(pdf_pages):
            next_pdf = pdf_pages[i+1]
            if next_pdf["first_anchor"]:
                end_idx = find_best_match(next_pdf["first_anchor"], docx_paras, (start_idx if start_idx else 0, min((start_idx if start_idx else 0) + 300, len(docx_paras))))
        
        # 3. 텍스트 추출
        final_start = start_idx if start_idx is not None else last_docx_ptr
        final_end = end_idx if end_idx is not None else (final_start + 1)
        
        # 만약 start와 end가 역전되거나 이상하면 보정
        if final_end <= final_start:
            final_end = final_start + 5 # 최소 범위

        matched_paras = docx_paras[final_start:final_end]
        matched_text = "\n\n".join([p["text"] for p in matched_paras])
        
        results.append({
            "page": pdf["page"],
            "pdf_text": pdf_text,
            "matched_text": matched_text
        })
        
        # 다음 검색 위치 업데이트
        if end_idx is not None:
            last_docx_ptr = end_idx
        elif start_idx is not None:
            last_docx_ptr = start_idx + 1

    return results

def run():
    target = "TOPIK_말하기_최종수정본_y251106"
    pdf_pages = extract_pdf_data(f"data/{target}.pdf")
    docx_paras = extract_docx_data(f"data/{target}.docx")
    
    results = match_logic(pdf_pages, docx_paras)
    
    with open("result.json", "w", encoding="utf-8") as f:
        json.dump(results, f, ensure_ascii=False, indent=2)
    
    # 로컬 브라우저 보안(CORS) 우회를 위해 JS 변수 형태로도 저장
    with open("result.js", "w", encoding="utf-8") as f:
        f.write("var resultDataRaw = " + json.dumps(results, ensure_ascii=False, indent=2) + ";")
    
    print(f"🚀 매칭 완료 (JSON + JS 데이터 생성 완료)")

if __name__ == "__main__":
    run()
