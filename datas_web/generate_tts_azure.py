import os
import json
import re
import time
import azure.cognitiveservices.speech as speechsdk

# === 설정 영역 ===
# Azure 구독 키와 지역(Region) 또는 엔드포인트(Endpoint)를 입력하세요.
# 환경 변수에 설정하거나 아래 변수에 직접 입력할 수 있습니다.
SPEECH_KEY = os.environ.get('SPEECH_KEY') or "YOUR_AZURE_SPEECH_KEY"
SPEECH_REGION = os.environ.get('SPEECH_REGION') or "koreacentral" # 예: "koreacentral", "eastus"
SPEECH_ENDPOINT = os.environ.get('SPEECH_ENDPOINT') # 엔드포인트가 필요한 경우만 입력

# 목소리 설정 (한국어 자연스러운 목소리 추천: ko-KR-SunHiNeural, ko-KR-InJoonNeural)
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

def synthesize_to_file(text, output_path):
    """텍스트를 MP3 파일로 변환"""
    if not SPEECH_KEY or SPEECH_KEY == "YOUR_AZURE_SPEECH_KEY":
        print("에러: SPEECH_KEY가 설정되지 않았습니다.")
        return False

    # 오디오 설정: 스피커 대신 파일로 출력
    audio_config = speechsdk.audio.AudioOutputConfig(filename=output_path)
    
    # 서비스 설정
    if SPEECH_ENDPOINT:
        speech_config = speechsdk.SpeechConfig(subscription=SPEECH_KEY, endpoint=SPEECH_ENDPOINT)
    else:
        speech_config = speechsdk.SpeechConfig(subscription=SPEECH_KEY, region=SPEECH_REGION)
    
    speech_config.speech_synthesis_voice_name = VOICE_NAME
    # MP3 형식 지정
    speech_config.set_speech_synthesis_output_format(speechsdk.SpeechSynthesisOutputFormat.Audio16Khz32KBitrateMonoMp3)

    synthesizer = speechsdk.SpeechSynthesizer(speech_config=speech_config, audio_config=audio_config)
    
    print(f"변환 중... [{text[:20]}...]")
    result = synthesizer.speak_text_async(text).get()

    if result.reason == speechsdk.ResultReason.SynthesizingAudioCompleted:
        return True
    elif result.reason == speechsdk.ResultReason.Canceled:
        cancellation_details = result.cancellation_details
        print(f"실패: {cancellation_details.reason}")
        if cancellation_details.reason == speechsdk.CancellationReason.Error:
            print(f"에러 상세: {cancellation_details.error_details}")
        return False

def main():
    if not os.path.exists(RESULT_JSON_PATH):
        print(f"에러: {RESULT_JSON_PATH} 파일을 찾을 수 없습니다.")
        return

    if not os.path.exists(OUTPUT_DIR):
        os.makedirs(OUTPUT_DIR)
        print(f"폴더 생성: {OUTPUT_DIR}")

    with open(RESULT_JSON_PATH, 'r', encoding='utf-8') as f:
        data = json.load(f)

    print(f"총 {len(data)}개 페이지 처리를 시작합니다.")

    for item in data:
        page_num = item.get('page')
        raw_text = item.get('pdf_text') or item.get('matched_text') or ""
        text_to_speak = clean_text(raw_text)

        if not text_to_speak:
            print(f"페이지 {page_num}: 텍스트가 없어 건너뜁니다.")
            continue

        filename = f"page_{page_num}.mp3"
        output_path = os.path.join(OUTPUT_DIR, filename)

        # 이미 파일이 있으면 건너뛰기 (선택 사항)
        if os.path.exists(output_path):
            print(f"페이지 {page_num}: 이미 파일이 존재합니다. 건너뜁니다.")
            continue

        success = synthesize_to_file(text_to_speak, output_path)
        if success:
            print(f"페이지 {page_num}: 저장 완료 -> {output_path}")
            # API 제한을 피하기 위한 짧은 휴식 (필요 시)
            time.sleep(0.5)
        else:
            print(f"페이지 {page_num}: 변환 실패")
            break

    print("\n모든 작업이 완료되었습니다.")

if __name__ == "__main__":
    main()
