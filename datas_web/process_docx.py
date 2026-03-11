import os
import zipfile
import xml.etree.ElementTree as ET
import re
import glob

# 설정
DATA_DIR = "data"
OUTPUT_DIR = os.path.join(DATA_DIR, "docx_pages")

# 네임스페이스 매핑
ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}

def ensure_dir(directory):
    if not os.path.exists(directory):
        os.makedirs(directory)
        print(f"📁 폴더 생성: {directory}")

def extract_text_with_images_and_pages(docx_path):
    if not zipfile.is_zipfile(docx_path):
        return {}, []

    try:
        with zipfile.ZipFile(docx_path) as zf:
            xml_content = zf.read('word/document.xml')
    except:
        return {}, []

    tree = ET.fromstring(xml_content)
    body = tree.find('w:body', ns)
    if body is None:
        # 네임스페이스 없이 다시 시도
        body = tree.find('.//body')
        if body is None:
             # 재귀적으로 body 찾기
             for elem in tree.iter():
                 if elem.tag.endswith('body'):
                     body = elem
                     break
    
    if body is None:
        return {}, []

    pages = {}
    current_page = 1
    current_text_lines = []
    
    # 전체 텍스트 수집용
    full_text_lines = []
    
    # 텍스트 패턴 (예: "1 강 - 문제 1")
    split_pattern = re.compile(r'^\s*\d+\s*강\s*[-–]\s*문제\s*\d+', re.IGNORECASE)

    print(f"  🔍 문서 스캔 시작...")
    
    # 모든 요소를 평탄화해서 순서대로 처리
    # (문단 안의 런, 런 안의 텍스트/브레이크 등을 순서대로 만남)
    for element in body.iter():
        tag_name = element.tag.split('}')[-1]
        
        # 1. 페이지 나누기 감지
        is_page_break = False
        
        # <w:br w:type="page"/>
        if tag_name == 'br':
            type_attr = element.get(f"{{{ns['w']}}}type")
            if type_attr == 'page':
                is_page_break = True
                # print("    -> 하드 페이지 브레이크 감지")
                
        # <w:lastRenderedPageBreak/>
        elif tag_name == 'lastRenderedPageBreak':
            is_page_break = True
            # print("    -> 렌더링 페이지 브레이크 감지")
            
        # 2. 텍스트 처리
        elif tag_name == 't':
            text = element.text or ""
            if text:
                current_text_lines.append(text)
                full_text_lines.append(text)
                
                # 텍스트 패턴 감지 (문단 시작이라 가정하고 단순화)
                # 주의: t 태그 단위로 쪼개져 있어서 완벽하진 않음
                if split_pattern.search(text):
                    print(f"    -> 텍스트 패턴 감지: {text}")
                    is_page_break = True

        # 3. 문단 끝 (줄바꿈)
        elif tag_name == 'p':
            current_text_lines.append("\n")
            full_text_lines.append("\n")

        # 페이지 나누기 실행
        if is_page_break:
            # 내용이 있을 때만 페이지 넘김
            page_content = "".join(current_text_lines).strip()
            if page_content:
                pages[current_page] = page_content
                current_page += 1
                current_text_lines = [] # 초기화

    # 마지막 페이지 저장
    page_content = "".join(current_text_lines).strip()
    if page_content:
        pages[current_page] = page_content
    
    # 전체 텍스트 조합
    full_text = "".join(full_text_lines).strip()

    return pages, full_text

import json

def process_all_docx():
    print("=" * 50)
    print("📘 DOCX -> 문제별 텍스트 추출기 (JSON)")
    print("=" * 50)
    
    ensure_dir(OUTPUT_DIR)
    
    docx_files = glob.glob(os.path.join(DATA_DIR, "*.docx"))
    
    # 문제 번호 패턴 (예: "문제 1", "문제 1.", "문제1")
    # 문단의 시작 부분에서 찾음
    problem_pattern = re.compile(r'^\s*문제\s*(\d+)', re.IGNORECASE)

    for docx_file in docx_files:
        filename = os.path.basename(docx_file)
        basename = os.path.splitext(filename)[0]
        
        print(f"\n📂 파일 처리 중: {filename}")
        
        # 1. 일단 전체 텍스트 라인을 가져옵니다.
        # (기존 함수 재활용하되 페이지 분할보다는 텍스트 순서가 중요)
        _, full_text = extract_text_with_images_and_pages(docx_file)
        
        if not full_text:
            print("  ⚠️ 텍스트를 추출하지 못했습니다.")
            continue
            
        # 2. 텍스트 라인별로 분석하여 문제 분리
        # extract_text_with_images_and_pages 함수가 줄바꿈(\n)을 포함하여 리턴하므로 split
        lines = full_text.split('\n')
        
        problems = {}
        current_problem_num = None
        current_problem_text = []
        
        # 머리말이나 문제 이전의 텍스트를 위한 버퍼 (필요시 '0'번이나 'intro'로 저장 가능)
        intro_text = []

        print(f"  🔍 텍스트 분석 중... (총 {len(lines)}줄)")

        for line in lines:
            stripped = line.strip()
            if not stripped:
                if current_problem_num:
                    current_problem_text.append(line) # 서식 유지를 위해 빈 줄도 포함
                continue
                
            match = problem_pattern.match(stripped)
            if match:
                # 새로운 문제 시작!
                
                # 이전 문제 저장
                if current_problem_num:
                    problems[current_problem_num] = "\n".join(current_problem_text).strip()
                    print(f"    -> 문제 {current_problem_num} 추출 완료 ({len(problems[current_problem_num])}자)")
                elif intro_text:
                    # 문제 1번 나오기 전의 텍스트
                    # problems['intro'] = "\n".join(intro_text).strip()
                    pass

                # 새 문제 시작
                current_problem_num = match.group(1) # 숫자 ("1")
                current_problem_text = [line] # 현재 줄부터 시작
                
            else:
                # 현재 문제에 텍스트 추가
                if current_problem_num:
                    current_problem_text.append(line)
                else:
                    intro_text.append(line)

        # 마지막 문제 저장
        if current_problem_num and current_problem_text:
             problems[current_problem_num] = "\n".join(current_problem_text).strip()
             print(f"    -> 문제 {current_problem_num} 추출 완료 ({len(problems[current_problem_num])}자)")
        
        problem_count = len(problems)
        print(f"  📊 총 {problem_count}개 문제 추출됨")
        
        if problem_count > 0:
            # JSON 저장
            output_filename = f"{basename}_problems.json"
            output_path = os.path.join(OUTPUT_DIR, output_filename)
            
            with open(output_path, "w", encoding="utf-8") as f:
                json.dump(problems, f, ensure_ascii=False, indent=2)
                
            print(f"  ✅ 문제 데이터 저장 완료: {output_filename}")
        else:
            print("  ⚠️ '문제 N' 형식을 찾지 못했습니다. 전체를 하나로 저장합니다.")
            output_filename = f"{basename}_full.txt"
            output_path = os.path.join(OUTPUT_DIR, output_filename)
            with open(output_path, "w", encoding="utf-8") as f:
                f.write(full_text)
            print(f"    -> 전체 텍스트 저장 완료 (fallback)")

if __name__ == "__main__":
    process_all_docx()
