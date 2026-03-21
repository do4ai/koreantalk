# Secure Viewer Integration

`datas_web`의 새 학생용 뷰어는 더 이상 `?url=https://...pdf` 방식으로 동작하지 않습니다.

목표는 두 가지입니다.

- 구매/권한 보유 회원만 전자책에 접근
- 브라우저 주소창에 원본 PDF 저장소 주소를 직접 노출하지 않음

## 권장 접근 흐름

1. 사용자는 Koreantalk 전자책 상세 화면에서 `학습 시작` 버튼을 누릅니다.
2. PHP 백엔드가 로그인 세션과 구매 권한을 확인합니다.
3. 백엔드는 짧은 수명의 `viewer session token`을 발급합니다.
4. 프론트는 `viewer-*.html?session=<opaque-token>` 형식으로 이동합니다.
5. 뷰어는 `/api/viewer/session/{token}`를 호출해 manifest를 받습니다.
6. manifest 안의 same-origin `pdf.stream_url` 또는 매우 짧은 TTL의 signed stream을 통해 PDF를 로드합니다.

## 왜 `?url=`을 제거했는가

- 원본 PDF URL을 학생에게 그대로 알려주면 뷰어를 우회해서 직접 접근할 수 있습니다.
- URL 파라미터는 브라우저 기록, 공유, 캡처, 로그에 남기 쉽습니다.
- UI에서 목록을 숨기는 것만으로는 접근통제가 되지 않습니다.

## 필수 보안 원칙

- `session` 파라미터에는 원본 파일 주소를 넣지 않습니다.
- `session` 값은 추측 불가능한 불투명 토큰이어야 합니다.
- `/api/viewer/session/{token}`는 반드시 로그인/구매 권한을 재검증해야 합니다.
- `pdf.stream_url`는 same-origin 프록시를 우선 권장합니다.
- signed URL을 써야 한다면 짧은 만료 시간과 IP/세션 연계를 고려합니다.
- 정답 공개 정책은 manifest에서 제어합니다.

## Manifest 예시

```json
{
  "mode": "exam",
  "expires_at": "2026-03-11T23:59:59+09:00",
  "book": {
    "title": "TOPIK II 실전 모의고사 1회",
    "subtitle": "듣기와 읽기를 한 화면에서 학습합니다.",
    "author": "KoreanTalk"
  },
  "access": {
    "member_name": "홍길동",
    "allow_download": false
  },
  "navigation": {
    "detail_url": "/kr/electron_book_v_1_0_0/electron_book_detail?electron_book_idx=15",
    "close_url": "/kr/my_order_v_1_0_0"
  },
  "pdf": {
    "stream_url": "/api/viewer/session/opaque-token/pdf",
    "page_count": 82
  },
  "exam": {
    "answer_visibility": "after_submit",
    "submit_copy": "이 페이지 답안을 제출했습니다.",
    "pages": {
      "9": [
        {
          "number": 1,
          "prompt": "알맞은 그림을 고르십시오.",
          "choices": ["가", "나", "다", "라"],
          "answer": 2,
          "explanation": "대화 내용의 핵심 단서가 3번 선택지에 있습니다."
        }
      ]
    }
  }
}
```

## 모드별 데이터

- `speaking.pages.{page}`: `script`, `prep_seconds`, `speak_seconds`, `note`
- `exam.pages.{page}`: 문항 배열
- `grammar.pages.{page}`: `summary`, `vocabulary`, `tips`

## 기존 데이터와의 관계

- `exam_data.js`, `result.js`는 개발 중 임시 fallback으로만 사용합니다.
- 운영 배포에서는 서버 manifest가 정본입니다.
- 학생용 UI에서는 로컬 문항 편집 기능을 제거했습니다.

## Koreantalk PHP 연동 포인트

- 상세 화면 버튼: 내부 viewer route로만 진입
- 권장 route 예시:
  - `/kr/electron_book_v_1_0_0/viewer?electron_book_idx=15`
- 컨트롤러 처리:
  - 로그인 체크
  - 구매 체크
  - 세션 토큰 생성
  - 뷰어 페이지로 redirect
- API 처리:
  - `GET /api/viewer/session/{token}`
  - `GET /api/viewer/session/{token}/pdf`

## 운영 체크리스트

- 원본 R2 public URL 직접 노출 금지
- 세션 만료 시간 짧게 유지
- CORS는 same-origin 또는 필요한 오리진으로만 제한
- 다운로드가 필요하면 별도 승인 정책 적용
- 정답 공개는 `after_submit` 또는 `never`를 기본값으로 운영
