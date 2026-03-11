from docx import Document
from docx.oxml.text.paragraph import CT_P
from docx.oxml.table import CT_Tbl
from docx.table import _Cell, Table
from docx.text.paragraph import Paragraph
from xml.etree.ElementTree import tostring
import json

def extract_pages_from_docx(docx_path, max_pages=5):
    """DOCX 파일에서 페이지 구분을 인식하여 페이지별로 텍스트 추출"""

    doc = Document(docx_path)

    print('========================================')
    print('DOCX 페이지별 텍스트 추출')
    print('========================================')
    print(f'파일: {docx_path}')
    print(f'최대 페이지: {max_pages}')
    print('')

    pages = {}
    current_page = 1
    current_page_text = []

    # 모든 요소를 순회 (단락과 테이블)
    for element in doc.element.body:
        if isinstance(element, CT_P):
            para = Paragraph(element, doc)
            text = para.text.strip()

            # 페이지 나누기 확인
            has_page_break = False
            para_xml_str = tostring(para._element, encoding='unicode')
            if 'w:type="page"' in para_xml_str or 'type="page"' in para_xml_str:
                has_page_break = True

            # 텍스트가 있으면 추가
            if text:
                current_page_text.append(text)

            # 페이지 나누기가 있으면 현재 페이지 저장하고 다음 페이지로
            if has_page_break:
                if current_page_text:
                    pages[current_page] = '\n'.join(current_page_text)
                    print(f'페이지 {current_page}: {len(current_page_text)}개 단락, {len(pages[current_page])}자')

                current_page += 1
                current_page_text = []

                # 최대 페이지 도달하면 중단
                if current_page > max_pages:
                    break

        elif isinstance(element, CT_Tbl):
            # 테이블은 "[표]"로 표시
            current_page_text.append('[표]')

    # 마지막 페이지 저장
    if current_page_text and current_page <= max_pages:
        pages[current_page] = '\n'.join(current_page_text)
        print(f'페이지 {current_page}: {len(current_page_text)}개 단락, {len(pages[current_page])}자')

    print('')
    print(f'총 {len(pages)}개 페이지 추출됨')
    print('========================================')

    return pages

def save_pages_to_json(pages, output_file='data/docx_pages.json'):
    """페이지별 텍스트를 JSON 파일로 저장"""
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(pages, f, ensure_ascii=False, indent=2)
    print(f'저장 완료: {output_file}')

def display_pages(pages):
    """페이지 내용 미리보기"""
    print('')
    print('========================================')
    print('페이지 내용 미리보기')
    print('========================================')
    for page_num, text in pages.items():
        print(f'\n[ 페이지 {page_num} ]')
        print('-' * 40)
        # 처음 200자만 표시
        preview = text[:200] + '...' if len(text) > 200 else text
        print(preview)
        print(f'(총 {len(text)}자)')

if __name__ == "__main__":
    docx_file = "data/TOPIK_말하기_최종수정본_y251106.docx"

    # 처음 5페이지만 추출
    pages = extract_pages_from_docx(docx_file, max_pages=5)

    # JSON으로 저장
    save_pages_to_json(pages)

    # 미리보기 출력
    display_pages(pages)

    print('')
    print('완료!')
