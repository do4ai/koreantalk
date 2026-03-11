import asyncio
import json
import os
import re
import edge_tts

# === 설정 영역 ===
# 목소리 추천: ko-KR-SunHiNeural (여성), ko-KR-InJoonNeural (남성)
VOICE_NAME = "ko-KR-SunHiNeural"

# 파일 경로 설정
RESULT_JSON_PATH = "result.json"
OUTPUT_DIR = os.path.join("data", "voice", "book1")
# ================

def clean_text(text):
    """PDF 텍스트에서 노이즈 제거 (pdf-stt.html 로직과 동기화)"""
    if not text: return ""
    # 3자 이상의 연속된 숫자열 줄 제거 (페이지 번호 등)
    text = re.sub(r'^[\d\s]{3,}\n', '', text, flags=re.MULTILINE)
    # Note. 또는 대답: 이후의 텍스트는 분리 (본문만 낭독하기 위함)
    split_match = re.search(r'(Note\s*\.?\s*|대\s*답\s*[:\.]?\s*\n)', text, re.IGNORECASE)
    if split_match:
        text = text[:split_match.start()].strip()
    return text.strip()

async def synthesize_page(text, output_path):
    """텍스트를 고품질 MP3로 변환 (Edge TTS 사용)"""
    try:
        communicate = edge_tts.Communicate(text, VOICE_NAME)
        await communicate.save(output_path)
        return True
    except Exception as e:
        print(f"변환 실패: {e}")
        return False

async def main():
    if not os.path.exists(RESULT_JSON_PATH):
        print(f"에러: {RESULT_JSON_PATH} 파일을 찾을 수 없습니다.")
        return

    if not os.path.exists(OUTPUT_DIR):
        os.makedirs(OUTPUT_DIR, exist_ok=True)
        print(f"폴더 생성: {OUTPUT_DIR}")

    with open(RESULT_JSON_PATH, 'r', encoding='utf-8') as f:
        data = json.load(f)

    print(f"총 {len(data)}개 페이지 분석 및 변환을 시작합니다.")

    # 전체를 다 하면 너무 오래 걸릴 수 있으므로 1페이지부터 순차적으로 진행
    # (사용자가 중단하고 싶으면 콘솔에서 중단 가능)
    for item in data:
        page_num = item.get('page')
        # 1페이지는 보통 표지이므로 텍스트가 없는 경우가 많음
        raw_text = item.get('pdf_text') or item.get('matched_text') or ""
        text_to_speak = clean_text(raw_text)

        if not text_to_speak:
            print(f"페이지 {page_num}: 낭독할 텍스트가 없어 건너뜁니다.")
            continue

        filename = f"page_{page_num}.mp3"
        output_path = os.path.join(OUTPUT_DIR, filename)

        # 이미 파일이 있으면 건너뛰기
        if os.path.exists(output_path):
            print(f"페이지 {page_num}: 이미 파일이 존재합니다.")
            continue

        print(f"페이지 {page_num} 변환 중... ({len(text_to_speak)}자)")
        success = await synthesize_page(text_to_speak, output_path)
        
        if success:
            print(f"페이지 {page_num} 저장 완료: {filename}")
        else:
            print(f"페이지 {page_num} 처리 중 에러 발생")

    print("\n[완료] 모든 페이지의 음성 변환 작업이 끝났습니다.")

if __name__ == "__main__":
    asyncio.run(main())
