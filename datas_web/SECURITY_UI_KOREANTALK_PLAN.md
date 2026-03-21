# Security UI Koreantalk Integration Plan

작성일: 2026-03-21

## 1. 확인 결과

### 브랜치 상태

- 현재 로컬 작업 브랜치는 `main`이며 작업 트리는 깨끗함
- 검토 대상 브랜치는 로컬 브랜치가 아니라 `origin/feature/security_ui`
- `main` 대비 추가 커밋은 1개
  - `96b9016 feat(viewer): secure student viewer session flow`

### `feature/security_ui` 변경 요약

- 기존 거대한 단일 HTML 뷰어 로직을 공통 CSS/JS로 분리
  - `datas_web/css/security-viewer.css`
  - `datas_web/js/security-viewer-app.js`
- 뷰어 HTML 4종을 세션 기반 보안 뷰어 셸로 단순화
  - `pdf-viewer.html`
  - `viewer-listening-reading.html`
  - `viewer-speaking.html`
  - `viewer-grammar.html`
- 핵심 진입 규칙 변경
  - 기존: `?url=https://...pdf`
  - 변경: `?session=<opaque-token>`
- 프론트가 기대하는 API 계약
  - `GET /api/viewer/session/{token}`
  - `GET /api/viewer/session/{token}/pdf`

### 운영 서비스 공개 구조 확인

2026-03-21 기준 공개 응답으로 확인한 내용:

- 접속 IP: `211.110.140.219`
- HTTP 접근 시 `https://koreantalk.net/`로 리다이렉트
- 메인 진입은 최종적으로 `https://koreantalk.net/kr/main_v_1_0_0`
- 서버 헤더는 `Apache`
- 세션 쿠키명은 `ci_session_koreantalk_www`
  - PHP + CodeIgniter 계열 구조로 보는 것이 타당함

### 서버 내부 구조 확인

2026-03-21 SSH 확인 결과:

- 서버 호스트명: `q391-3160.cafe24.com`
- OS: `CentOS Linux 7.6.1810`
- Apache 실행 파일: `/opt/apache/bin/httpd`
- vhost 설정 파일:
  - `/opt/apache/conf/extra/httpd-vhosts.conf`
  - `/opt/apache/conf/extra/koreantalk.net-ssl.conf`
- 운영 도메인 DocumentRoot:
  - HTTP `www.koreantalk.net` -> `/home/koreantalk/www/`
  - HTTPS `koreantalk.net`, `www.koreantalk.net` -> `/home/koreantalk/www/`
- 관리자/기타 서브도메인:
  - `admin.koreantalk.net` -> `/home/koreantalk/admin/`
  - `pw.koreantalk.net` -> `/home/koreantalk/pw/`
- 실제 서비스 엔트리 파일:
  - `/home/koreantalk/www/index.php`
- `index.php` 부트 경로:
  - system -> `../CI/system`
  - application -> `../CI/www/application`

즉, 실제 운영 웹 루트는 `/home/koreantalk/www` 이고, 비즈니스 코드는 `/home/koreantalk/CI/www/application` 아래 HMVC 모듈 구조로 운영 중임.

### 공개 라우팅 패턴

- 메인: `/kr/main_v_1_0_0`
- 서점 목록: `/kr/product_v_1_0_0`
- 상품 상세: `/kr/product_v_1_0_0/product_detail?product_idx={id}`
- 전자책 목록: `/kr/electron_book_v_1_0_0`
- 전자책 목록 AJAX: `/kr/electron_book_v_1_0_0/electron_book_list_get`
- 로그인: `/kr/login_v_1_0_0`
- 주문/내 주문: `/kr/my_order_v_1_0_0`
- 게시판: `/kr/board_v_1_0_0?category={id}`
- 1:1 문의: `/kr/qa_v_1_0_0`

### 공개 페이지 기준 관찰된 서비스 구조

- 메인 네비게이션은 `TOPIK / 서점 / 커뮤니티 / 문화`
- 서점 하위에는 TOPIK 교재 상세 페이지가 직접 매핑됨
  - 예: `product_idx=78` 는 `TOPIK2 듣기/읽기`
- 전자책 목록 페이지는 서버 렌더 후 AJAX로 목록 HTML을 채움
- 비로그인 공개 상태의 `/kr/electron_book_v_1_0_0/electron_book_list_get` 응답은 `등록된 책이 없습니다.`
- `/kr/my_order_v_1_0_0` 는 로그인 리다이렉트가 걸려 있음
  - 비로그인 기준 `//login_v_1_0_0?return_url=/my_order_v_1_0_0` 형태로 이동

### 실제 코드 모듈 구조

- 공통 버전 매핑:
  - `/home/koreantalk/CI/www/application/helpers/version_mapping_helper.php`
- 공통 레이아웃 로더:
  - `/home/koreantalk/CI/www/application/core/MY_Controller.php`
- 메인:
  - `/home/koreantalk/CI/www/application/modules/main/main_v_1_0_0`
- 서점:
  - `/home/koreantalk/CI/www/application/modules/product/product_v_1_0_0`
- 전자책:
  - `/home/koreantalk/CI/www/application/modules/electron_book/electron_book_v_1_0_0`
- 로그인:
  - `/home/koreantalk/CI/www/application/modules/login/login_v_1_0_0`
- 구매내역:
  - `/home/koreantalk/CI/www/application/modules/my_order/my_order_v_1_0_0`

운영 사이트는 `application/modules/{domain}/{version}` 패턴으로 기능을 분리한 HMVC 구조임.

## 2. SSH 확인 상태

- `KT_PW` export 이후 SSH root 접속 성공
- 서버 내부에서 DocumentRoot, CodeIgniter application 경로, 전자책/주문 모듈 경로를 확인함
- `security_ui` 브랜치의 정적 뷰어 파일은 현재 서버에 아직 배포되어 있지 않음
  - 검색 기준 미존재:
    - `viewer-speaking.html`
    - `viewer-listening-reading.html`
    - `pdf-viewer.html`
    - `security-viewer-app.js`
    - `security-viewer.css`

## 3. 현재 구조에 붙이는 방향

### 운영 플로우 해석

현재 Koreantalk 운영 구조에 가장 자연스럽게 맞는 흐름은 아래와 같음.

1. 사용자가 `서점` 또는 `내 주문`에서 교재/전자책 상세로 진입
2. 로그인과 구매 여부를 PHP 서버가 확인
3. 학습 시작 버튼 클릭 시 서버가 짧은 TTL의 뷰어 세션 토큰 발급
4. 서버가 `viewer-*.html?session=<opaque-token>` 으로 이동
5. 뷰어는 `/api/viewer/session/{token}` 에서 manifest 수신
6. same-origin PDF 프록시 `/api/viewer/session/{token}/pdf` 로 본문 로드

이 흐름은 현재 운영 사이트의 `CodeIgniter 스타일 라우팅 + 서버 렌더 상세 페이지 + 로그인 세션` 구조와 충돌하지 않음.

### 현재 운영 구현과의 실제 접점

- 전자책 상세 컨트롤러:
  - `Electron_book_v_1_0_0::electron_book_detail()`
- 전자책 상세 뷰:
  - `view_electron_book_detail.php`
- 내 주문 컨트롤러:
  - `My_order_v_1_0_0::order_list_get()`
  - `My_order_v_1_0_0::file_download()`
- 내 주문 뷰:
  - `view_my_order_list.php`
  - `view_my_order_list_get.php`

현재 사용자 진입은 이미 아래 형태로 만들어져 있음.

1. 전자책 상세 진입
2. 구매 완료 시 `my_order` 에서 구매 목록 노출
3. 구매 목록에서 `PDF 다운로드` 버튼 클릭
4. `file_download?product_auth_code=...` 로 실제 파일 다운로드

즉, 기존 `PDF 다운로드` 동선을 `보안 뷰어 세션 발급 -> viewer 진입` 으로 대체하는 것이 가장 자연스럽다.

### 붙일 위치

우선순위 기준 권장 진입점:

1. `electron_book_detail`
   - 구매 완료 사용자의 기본 학습 시작 CTA
2. `my_order`
   - 이미 구매한 사용자의 재진입 CTA
3. `product_detail`
   - 미구매 사용자에게는 구매 안내
   - 구매 사용자에게는 전자책 상세 또는 바로 학습 시작 연결

## 4. 1차 기획안

### A. 사용자 동선 기획

#### 1. 상품 상세

- 상품 상세에는 전자책 여부를 명확히 분리 표시
- 로그인 전:
  - `로그인 후 구매/학습 가능`
- 구매 전:
  - `구매하기`
- 구매 후:
  - `전자책 상세 보기`
  - 또는 `학습 시작`

#### 2. 전자책 상세

- 전자책별 소개, 학습 모드, 저자, 페이지 수, 학습 안내 노출
- 진입 CTA는 모드별로 분기
  - 듣기/읽기: `학습 시작`
  - 말하기: `말하기 연습 시작`
  - 문법/어휘: `문법 학습 시작`
- 이 페이지가 `security_ui` 구조를 붙이는 핵심 거점이 됨

#### 3. 내 주문 / 내 학습

- 구매 목록에서 바로 진입 버튼 제공
- 최근 학습 교재, 마지막 페이지, 학습 만료일 표시
- 추후 `내 학습` 전용 메뉴로 확장 가능

### B. 백엔드 기획

#### 1. 라우트

권장 라우트:

- `GET /kr/electron_book_v_1_0_0/viewer?electron_book_idx={id}`
- `GET /api/viewer/session/{token}`
- `GET /api/viewer/session/{token}/pdf`

추가 권장:

- 기존 `my_order/file_download` 는 단계적으로 `viewer_entry` 또는 `viewer_redirect` 로 전환
- 다운로드가 꼭 필요하지 않다면 `force_download()` 는 운영 기본 경로에서 제거

필요 시 모드 분리:

- `viewer/listening-reading`
- `viewer/speaking`
- `viewer/grammar`

단, 프론트는 동일 앱을 쓰고 `manifest.mode` 또는 `body[data-viewer-mode]` 로 분기하므로 서버 라우트는 하나로 유지해도 됨.

#### 2. 서버 검증 규칙

- 로그인 여부 확인
- 결제/권한 여부 확인
- 만료/환불/관리자 비활성 여부 확인
- 토큰 TTL 짧게 유지
- 토큰 1회성 또는 세션 연동 여부 결정
- 원본 PDF URL 직접 노출 금지

현재 운영 코드 기준 반드시 바꿔야 하는 부분:

- `My_order_v_1_0_0::file_download()` 는 파일을 직접 읽어 `force_download()` 함
- `tbl_electron_book.pdf_url` 에 저장된 `/media/commonfile/...pdf` 가 공개 정적 URL로 그대로 접근 가능함
- 실제 확인 결과 `https://koreantalk.net/media/commonfile/202410/13/727b6f9293f750163c09c052387101e6.pdf` 는 `200 OK` 응답

따라서 `security_ui` 적용의 본질은 단순 UI 교체가 아니라:

1. 공개 PDF 직접 접근 차단
2. 주문 인증 기반 세션 발급
3. same-origin 프록시 스트림 제공
4. 기존 다운로드/공개 링크 제거

#### 3. manifest 구성

필수 필드:

- `mode`
- `expires_at`
- `book.title`
- `navigation.detail_url`
- `navigation.close_url`
- `pdf.stream_url`

모드별 확장:

- 시험형: `exam.pages`
- 말하기형: `speaking.pages`
- 문법형: `grammar.pages`

## 5. UI 기획 포인트

### 공통

- 상단에 교재명, 만료 시간, 현재 페이지, 보안 상태를 유지
- 모바일에서는 우측 사이드바를 하단 시트 또는 탭으로 전환
- 만료 직전 경고 배너 필요
- 세션 만료 시 즉시 상세 페이지로 복귀시키지 말고 재진입 CTA 제공

### 시험형

- 현재 페이지 문제 풀이
- 페이지별 제출, 전체 진행률, 정답 공개 정책 표시
- `after_submit` 과 `never` 정책을 UI 문구로 명확히 구분

### 말하기형

- 준비 시간, 발화 시간, 스크립트, 노트
- 녹음 저장을 붙일지 여부는 2차 과제로 분리

### 문법형

- 요약, 어휘, 팁을 페이지 단위로 매핑
- PDF 본문과 우측 학습 패널 간 정보 밀도 균형 필요

## 6. 바로 필요한 구현 항목

### 1단계

- 공개 PDF 직접 접근 차단 정책 수립
- PHP 컨트롤러에 viewer 진입 라우트 생성
- 세션 토큰 발급 로직 생성
- manifest API 생성
- PDF 프록시 API 생성
- `datas_web` 뷰어 파일 배포 위치 결정
  - 권장: `/home/koreantalk/www/secure-viewer/` 또는 현재 웹 루트 하위 전용 디렉터리

### 2단계

- `electron_book_detail` 화면에 `학습 시작` 버튼 연결
- `my_order` 에 구매 전자책 CTA 추가
- 운영 manifest 생성용 관리자 입력 방식 정의

### 3단계

- 학습 이력 저장
- 마지막 페이지 이어보기
- 제출 로그 저장
- 관리자에서 정답 공개 정책 제어

## 7. 현재 기준 결론

- `feature/security_ui` 는 현재 Koreantalk 운영 사이트에 붙일 수 있는 형태로 잘 정리되어 있음
- 공개 운영 구조는 `서버 렌더 PHP + 로그인 세션 + /kr/*_v_1_0_0 라우팅` 중심
- 따라서 별도 SPA로 떼기보다 `electron_book_detail` 과 `my_order` 를 기점으로 세션 기반 뷰어를 삽입하는 방식이 가장 자연스러움
- 가장 먼저 해야 할 일은 프론트 추가 개발이 아니라 PHP 쪽 `viewer session` 발급과 PDF 프록시 API 구현임
- 현재 운영은 PDF 원본이 `/media/commonfile/*.pdf` 로 공개 노출되어 있으므로, 보안 검수 기준에서 우선순위 1번은 직접 URL 차단이다

## 8. 남은 확인 항목

남은 확인 항목:

- `datas_web` 뷰어 파일 실제 배포 방식
- viewer용 신규 route 명명 규칙
- 세션 토큰 저장 방식
  - DB 저장
  - Redis/파일 세션 연계
  - 서명 토큰만 사용
- 기존 구매 완료 회원에 대한 마이그레이션 전략
- 다운로드 허용 정책 유지 여부
- `/media/commonfile/*.pdf` 차단 시 기존 링크 영향 범위
- 관리자에서 manifest 데이터를 어디서 관리할지
