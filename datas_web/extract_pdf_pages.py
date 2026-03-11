import pdfplumber
import json
import os

def extract_pdf_text_by_page(pdf_path, output_dir='data/hwp_pages'):
    """PDF 파일에서 페이지별로 텍스트를 추출하여 텍스트 파일로 저장"""

    # 출력 디렉토리 생성
    if not os.path.exists(output_dir):
        os.makedirs(output_dir)

    # PDF 파일명 추출 (확장자 제외)
    base_name = os.path.splitext(os.path.basename(pdf_path))[0]

    print(f'========================================')
    print(f'📄 PDF 파일 텍스트 추출 시작')
    print(f'========================================')
    print(f'파일: {pdf_path}')
    print(f'출력 경로: {output_dir}')
    print(f'')

    # PDF 열기
    with pdfplumber.open(pdf_path) as pdf:
        total_pages = len(pdf.pages)
        print(f'총 페이지: {total_pages}')
        print(f'========================================\n')

        success_count = 0
        empty_count = 0

        # 각 페이지별로 텍스트 추출
        for page_num in range(1, total_pages + 1):
            try:
                page = pdf.pages[page_num - 1]  # 0-based index
                text = page.extract_text()

                if text and text.strip():
                    # 텍스트 파일로 저장
                    output_file = os.path.join(output_dir, f'{base_name}_page{page_num}.txt')
                    with open(output_file, 'w', encoding='utf-8') as f:
                        f.write(text.strip())

                    success_count += 1

                    # 진행 상황 표시 (10페이지마다)
                    if page_num % 10 == 0 or page_num == total_pages:
                        print(f'✅ 페이지 {page_num}/{total_pages} 처리 완료 ({len(text)} 자)')
                else:
                    empty_count += 1
                    if page_num <= 10:  # 처음 10페이지만 빈 페이지 알림
                        print(f'⚠️  페이지 {page_num}: 텍스트 없음')

            except Exception as e:
                print(f'❌ 페이지 {page_num} 오류: {e}')

        print(f'\n========================================')
        print(f'📊 추출 완료')
        print(f'========================================')
        print(f'✅ 성공: {success_count}개 페이지')
        print(f'⚠️  빈 페이지: {empty_count}개')
        print(f'📁 저장 위치: {output_dir}')
        print(f'========================================')

        return success_count

if __name__ == "__main__":
    pdf_file = "data/TOPIK_말하기_최종수정본_y251106.pdf"

    if not os.path.exists(pdf_file):
        print(f'❌ 파일을 찾을 수 없습니다: {pdf_file}')
    else:
        extract_pdf_text_by_page(pdf_file)
        print('\n✅ 완료! 이제 PDF viewer를 새로고침하면 자동으로 텍스트가 로드됩니다.')
