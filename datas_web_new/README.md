# `datas_web_new`

코리안톡 PHP 화면에 바로 붙이기 위한 최소 speaking 뷰어 패키지입니다.

포함 파일:

- `viewer-speaking.php`
- `css/style.css`
- `css/pdf-viewer.css`
- `result.js`
- `exam_data.js`

제외한 것:

- 세션 manifest API 의존 코드
- 로컬 dev server
- 보안 뷰어 전용 CSS/JS
- 음성 가이드 파일 `data/*`

## PHP에서 넘겨줄 값

`viewer-speaking.php` 렌더 전에 `$viewer_bootstrap` 배열만 주입하면 됩니다.

```php
$viewer_bootstrap = array(
    'title' => 'TOPIK Speaking',
    'mode' => 'speaking',
    'electron_book_idx' => 15,
    'product_auth_code' => 'P_...',
    'pdf_url' => '/us/electron_book_v_1_0_0/study_pdf?product_auth_code=P_...'
);
```

`pdf_url`은 공개 PDF 경로가 아니라, 구매 검증 후 PDF를 스트림하는 PHP URL이어야 합니다.

## 동작 방식

- PHP가 로그인/구매 여부를 먼저 검증
- PHP가 `$viewer_bootstrap`를 JSON으로 주입
- 뷰어는 `pdf_url`을 `fetch()`로 읽어 PDF.js에 전달
- speaking UI와 문제/시간 데이터는 기존 `result.js`, `exam_data.js`를 그대로 사용

## 주의

- 현재 패키지는 speaking 뷰어 기준입니다.
- `data/` 음원 파일을 포함하지 않았기 때문에 가이드/시작 사운드는 기본값이 꺼져 있습니다.
