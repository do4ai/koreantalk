# KoreanTalk - TOPIK 학습 뷰어

## Secure Viewer Update

학생용 전자책 뷰어는 `feature/security_ui` 브랜치부터 `?url=` 직접 로드 방식을 사용하지 않습니다.

- 진입 방식: `viewer-*.html?session=<opaque-token>`
- 권장 세션 API: `GET /api/viewer/session/{token}`
- 상세 설계 문서: [SECURE_VIEWER_INTEGRATION.md](./SECURE_VIEWER_INTEGRATION.md)

기존 `exam_data.js`, `result.js`는 개발 fallback 용도로만 유지하고, 운영에서는 서버가 내려주는 manifest JSON을 정본으로 사용합니다.

# KoreanTalk - TOPIK 말하기 연습

TOPIK 한국어 말하기 연습을 위한 인터랙티브 웹사이트입니다.

## 주요 기능

- 📚 Chapter별 한국어 대화 학습
- 🔊 고품질 TTS (Text-to-Speech) 음성 지원
- 👥 화자별 다른 목소리로 자연스러운 대화 연습
- 🎯 개별 문장 재생 & 전체 대화 재생
- 📱 반응형 디자인 (모바일/태블릿/PC 지원)

## Google Cloud TTS API 설정 방법

이 프로젝트는 **Google Cloud Text-to-Speech API**를 사용합니다.

### 비용 정보
- **무료 할당량**: 월 400만 글자
- **추가 비용**: $4 / 100만 글자 (매우 저렴!)
- 일반적인 사용으로는 무료 할당량 내에서 충분히 사용 가능합니다

### 1. Google Cloud 프로젝트 생성

1. [Google Cloud Console](https://console.cloud.google.com/)에 접속
2. 새 프로젝트 생성 또는 기존 프로젝트 선택
3. 결제 계정 연결 (무료 평가판 사용 가능)

### 2. Text-to-Speech API 활성화

1. Google Cloud Console에서 [API 라이브러리](https://console.cloud.google.com/apis/library) 이동
2. "Cloud Text-to-Speech API" 검색
3. **"사용 설정"** 클릭

### 3. API 키 생성

1. [API 및 서비스 > 사용자 인증 정보](https://console.cloud.google.com/apis/credentials) 페이지로 이동
2. **"+ 사용자 인증 정보 만들기"** 클릭
3. **"API 키"** 선택
4. 생성된 API 키 복사

### 4. API 키 설정

`js/config.js` 파일을 열고 API 키를 입력하세요:

```javascript
const GOOGLE_TTS_CONFIG = {
    apiKey: 'YOUR_API_KEY_HERE',  // 여기에 복사한 API 키 붙여넣기
    endpoint: 'https://texttospeech.googleapis.com/v1/text:synthesize'
};
```

### 5. API 키 보안 (선택사항)

**중요**: API 키를 공개 저장소에 올리지 마세요!

권장 방법:
- `.gitignore`에 `config.js` 추가
- 환경 변수 사용
- 백엔드 서버를 통한 프록시 설정

## 사용 방법

1. `index.html` 파일을 브라우저에서 열기
2. 원하는 Chapter 선택
3. 🔊 버튼을 클릭하여 음성 듣기
   - 개별 문장 듣기: 각 대화 옆 "🔊 듣기" 버튼
   - 전체 듣기: 하단 "🔊 전체 듣기" 버튼

## 음성 설정 커스터마이징

`js/config.js`에서 화자별 음성을 변경할 수 있습니다:

```javascript
const VOICE_SETTINGS = {
    '간호사': {
        name: 'ko-KR-Wavenet-A',  // 여성 목소리
        gender: 'FEMALE'
    },
    '환자': {
        name: 'ko-KR-Wavenet-C',  // 남성 목소리
        gender: 'MALE'
    },
    // ... 더 많은 설정
};
```

### 사용 가능한 한국어 음성

| 음성 이름 | 성별 | 품질 |
|----------|------|------|
| ko-KR-Wavenet-A | Female | 고품질 (WaveNet) |
| ko-KR-Wavenet-B | Female | 고품질 (WaveNet) |
| ko-KR-Wavenet-C | Male | 고품질 (WaveNet) |
| ko-KR-Wavenet-D | Male | 고품질 (WaveNet) |
| ko-KR-Standard-A | Female | 표준 |
| ko-KR-Standard-B | Female | 표준 |
| ko-KR-Standard-C | Male | 표준 |
| ko-KR-Standard-D | Male | 표준 |

더 많은 음성 옵션: [Google Cloud TTS 음성 목록](https://cloud.google.com/text-to-speech/docs/voices)

## 이미지 교체 방법

`images/` 폴더의 이미지 파일을 실사 이미지로 교체하세요:

- `hospital.jpg` - 병원 이미지
- `restaurant.jpg` - 식당 이미지
- `school.jpg` - 학교 이미지

권장 사양:
- 해상도: 800x500px
- 형식: JPG, PNG
- 용량: 500KB 이하

## 새 Chapter 추가 방법

1. `chapters/chapter1.html`을 복사하여 새 파일 생성
2. 챕터 번호와 대화 내용 수정
3. `images/` 폴더에 해당 이미지 추가
4. `index.html`에 새 챕터 링크 추가
5. `js/config.js`에 새로운 화자 추가 (필요시)

## 프로젝트 구조

```
KoreanTalk/
├── index.html              # 메인 페이지 (목차)
├── css/
│   └── style.css          # 전체 스타일시트
├── js/
│   ├── config.js          # TTS API 설정
│   └── tts.js             # TTS 기능 구현
├── chapters/
│   ├── chapter1.html      # Chapter 1: 병원
│   ├── chapter2.html      # Chapter 2: 식당
│   └── chapter3.html      # Chapter 3: 학교
├── images/
│   ├── hospital.jpg
│   ├── restaurant.jpg
│   └── school.jpg
└── README.md              # 이 파일
```

## 기술 스택

- HTML5
- CSS3 (Flexbox, Grid)
- Vanilla JavaScript (ES6+)
- Google Cloud Text-to-Speech API

## 특징

1. **오디오 캐싱**: 같은 문장을 다시 재생할 때 비용 절감
2. **화자별 음성**: 각 화자마다 다른 목소리 자동 할당
3. **순차 재생**: 전체 듣기 시 자연스러운 대화 흐름
4. **시각적 피드백**: 현재 재생 중인 문장 하이라이트
5. **반응형 디자인**: 모든 디바이스에서 최적화된 경험

## 문제 해결

### "API 키가 설정되지 않았습니다" 오류
- `js/config.js` 파일에 API 키를 올바르게 입력했는지 확인하세요

### "음성 생성에 실패했습니다" 오류
1. API 키가 유효한지 확인
2. Google Cloud Console에서 Text-to-Speech API가 활성화되어 있는지 확인
3. 인터넷 연결 확인
4. 브라우저 콘솔(F12)에서 상세 오류 확인

### 음성이 재생되지 않음
- 브라우저에서 자동 재생이 차단되어 있을 수 있습니다
- 사용자 제스처(버튼 클릭) 후에만 재생됩니다

## 라이선스

이 프로젝트는 교육 목적으로 자유롭게 사용 가능합니다.

## 문의

문제가 발생하거나 기능 제안이 있으시면 이슈를 등록해주세요.

---

**Made with ❤️ for Korean language learners**
