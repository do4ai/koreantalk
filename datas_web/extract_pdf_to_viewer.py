import pdfplumber
import json

def extract_pdf_pages(pdf_path, max_pages=5):
    """PDF에서 페이지별로 텍스트 추출"""

    print('========================================')
    print('PDF 페이지별 텍스트 추출')
    print('========================================')
    print(f'파일: {pdf_path}')
    print(f'최대 페이지: {max_pages}')
    print('')

    pages = {}

    with pdfplumber.open(pdf_path) as pdf:
        total = min(max_pages, len(pdf.pages))

        for page_num in range(1, total + 1):
            page = pdf.pages[page_num - 1]
            text = page.extract_text()

            if text and text.strip():
                pages[str(page_num)] = text.strip()
                print(f'페이지 {page_num}/{total}: {len(text.strip())}자')
            else:
                print(f'페이지 {page_num}/{total}: 빈 페이지')

    print('')
    print(f'총 {len(pages)}개 페이지 추출됨')
    print('========================================')

    return pages

def save_to_json(pages, output_file='data/pdf_pages_test.json'):
    """JSON으로 저장"""
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(pages, f, ensure_ascii=False, indent=2)
    print(f'저장 완료: {output_file}')

def display_preview(pages):
    """페이지 내용 미리보기"""
    print('')
    print('========================================')
    print('페이지 내용 미리보기')
    print('========================================')
    for page_num, text in pages.items():
        print(f'\n[ 페이지 {page_num} ]')
        print('-' * 40)
        preview = text[:200] + '...' if len(text) > 200 else text
        print(preview)
        print(f'(총 {len(text)}자)')

if __name__ == "__main__":
    pdf_file = "data/TOPIK_말하기_최종수정본_y251106.pdf"

    # 처음 5페이지 추출
    pages = extract_pdf_pages(pdf_file, max_pages=5)

    # JSON 저장
    save_to_json(pages)

    # 미리보기
    display_preview(pages)

    print('')
    print('완료!')
