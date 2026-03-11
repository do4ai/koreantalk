from docx import Document
from docx.oxml.text.paragraph import CT_P
from xml.etree.ElementTree import tostring

def debug_docx_structure(docx_path, max_paras=50):
    """DOCX의 페이지 구조를 디버깅"""

    doc = Document(docx_path)

    print('DOCX 구조 분석')
    print('=' * 60)

    para_count = 0
    page_break_count = 0

    for element in doc.element.body:
        if isinstance(element, CT_P):
            para_count += 1

            if para_count > max_paras:
                break

            from docx.text.paragraph import Paragraph
            para = Paragraph(element, doc)
            text = para.text.strip()

            # XML 확인
            xml_str = tostring(para._element, encoding='unicode')

            # 페이지 브레이크 찾기
            has_page_break = 'w:br' in xml_str and 'type="page"' in xml_str

            if has_page_break:
                page_break_count += 1
                print(f'\n[페이지 브레이크 발견! #{page_break_count}]')
                print(f'단락 번호: {para_count}')
                print(f'텍스트: {text[:100] if text else "(없음)"}')
                print(f'XML 일부: {xml_str[:300]}...')
                print('-' * 60)
            elif para_count <= 10:
                # 처음 10개 단락만 표시
                print(f'\n단락 {para_count}:')
                print(f'텍스트: {text[:100] if text else "(없음)"}')
                if 'w:br' in xml_str:
                    print('  -> br 태그 있음 (but not page break)')

    print(f'\n총 {para_count}개 단락 확인')
    print(f'페이지 브레이크 {page_break_count}개 발견')

if __name__ == "__main__":
    debug_docx_structure("data/TOPIK_말하기_최종수정본_y251106.docx", max_paras=100)
