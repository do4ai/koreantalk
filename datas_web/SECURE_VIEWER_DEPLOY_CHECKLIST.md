# Secure Viewer Deploy Checklist

## 범위

- 이 저장소에서 수정하는 범위는 `datas_web` 정적 자산과 연동 문서만입니다.
- 고객사 운영 PHP, Apache, DB는 이 저장소에서 직접 수정하지 않습니다.
- 이 문서는 `datas_web` 산출물을 운영 서버에 붙일 때 필요한 체크리스트입니다.

## 정적 배포 자산

운영 서버에 배포할 핵심 파일:

- `viewer-listening-reading.html`
- `viewer-speaking.html`
- `viewer-grammar.html`
- `pdf-viewer.html`
- `css/security-viewer.css`
- `js/security-viewer-app.js`

권장 배포 경로:

- `/home/koreantalk/www/secure-viewer/`

권장 공개 URL 예시:

- `/secure-viewer/viewer-listening-reading.html?session=<opaque-token>`
- `/secure-viewer/viewer-speaking.html?session=<opaque-token>`
- `/secure-viewer/viewer-grammar.html?session=<opaque-token>`
- `/secure-viewer/pdf-viewer.html?session=<opaque-token>`

## 서버 연동 요구사항

정적 뷰어만 올려서는 동작하지 않습니다. 서버는 반드시 아래 계약을 제공해야 합니다.

- `GET /api/viewer/session/{token}`
- `GET /api/viewer/session/{token}/pdf`

`session` API는 다음을 검증해야 합니다.

- 로그인 세션 존재
- 구매 이력 존재
- 환불/취소/비활성 상태 아님
- 토큰 만료 전
- 필요 시 IP/UA 바인딩 일치

`pdf` API는 다음을 만족해야 합니다.

- 원본 PDF 공개 URL을 응답하지 않음
- same-origin 응답
- 직접 다운로드보다 인라인 스트림 우선
- range 요청 또는 전체 응답 중 운영 브라우저 호환 방식 선택

## 운영 전 필수 확인

- 뷰어 진입 버튼은 기존 `file_download` 와 병행 노출하거나 기능 플래그 뒤에 둡니다.
- direct PDF URL(`/media/commonfile/*.pdf`) 차단 계획이 준비되어 있어야 합니다.
- manifest 데이터 정본은 서버에서 내려주고, `exam_data.js`/`result.js`는 fallback으로만 남깁니다.
- 토큰 만료 UX와 에러 메시지를 운영 언어로 맞춥니다.

## QA 체크리스트

- 비로그인 사용자는 로그인 페이지로 이동
- 로그인했지만 미구매 사용자는 진입 차단
- 구매 사용자는 `상품 상세 -> 학습하기` 진입 가능
- 구매 사용자는 `내 주문 -> 학습하기` 재진입 가능
- `?session=` 없이 접근 시 차단 화면 표시
- 만료 토큰 접근 차단
- 다른 계정 토큰 재사용 차단
- PDF 직접 URL이 외부에 노출되지 않음
- 듣기/읽기, 말하기, 문법 모드별 UI 정상
- 모바일/데스크톱에서 PDF 렌더 및 문제 풀이 정상

## 롤백 원칙

- 서버 버튼만 기존 `file_download` 로 되돌리면 즉시 사용자 영향 차단 가능
- 정적 `secure-viewer` 파일은 서버에 남아 있어도 무방
- `api/viewer/*` 라우트 비활성화 시 신규 진입 차단 가능
- direct PDF 차단은 secure viewer 검수 완료 후 마지막 단계에서 적용
