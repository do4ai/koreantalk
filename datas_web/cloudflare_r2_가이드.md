# Cloudflare R2를 이용한 고속 PDF 파일 호스팅 가이드

이 문서에서는 100MB가 넘는 PDF 파일들을 안전하고 빠르게, 그리고 **무료로(또는 거의 0원에)** 서비스하기 위해 Cloudflare R2 스토리지를 세팅하고 Vercel 앱과 연동하는 방법을 단계별로 안내합니다.

---

## 1단계: Cloudflare 가입 및 R2 비전 활성화

1. **회원가입**: [Cloudflare](https://dash.cloudflare.com/sign-up) 사이트에 접속하여 무료 회원가입을 진행합니다.
2. **R2 메뉴 이동**: 로그인 후 왼쪽 사이드바에서 **[R2]** 또는 **[R2 Object Storage]** 메뉴를 클릭합니다.
3. **결제 수단 등록 (최초 1회)**: R2를 처음 사용할 때는 결제용 카드 등록이 필요합니다. 
   > **요금 안심 안내**: R2는 기본적으로 매월 **10GB 저장공간 무료 제공**, **데이터 전송 비용(Egress) 평생 완전 무료**입니다. PDF 조회용으로만 쓴다면 요금 폭탄 맞을 일이 절대 없습니다!

## 2단계: 파일 창고(Bucket) 만들기

1. R2 대시보드에서 파란색 **[Create bucket (버킷 만들기)]** 버튼을 클릭합니다.
2. 버킷 이름(Bucket name)을 정합니다. (예: `koreantalk-pdf-storage`)
   * *이름은 영문 소문자와 하이픈(-)만 가능하며 고유해야 합니다.*
3. Location(위치) 설정은 `Automatic (자동)`으로 둡니다.
4. **[Create bucket]**을 눌러 생성을 완료합니다.

## 3단계: PDF 파일 업로드하기

1. 방금 만든 버킷 이름을 클릭하여 관리 창으로 들어갑니다.
2. **[Upload]** 버튼을 클릭하거나, 100MB짜리 PDF 파일(예: `TOPIK_Exam_1.pdf`)을 화면에 드래그 앤 드롭하여 업로드합니다.

## 4단계: 파일을 외부에서 접속 가능하게 열어주기 (Public Access)

업로드된 파일은 기본적으로 '비공개(Private)' 상태입니다. 고객이 볼 수 있게 외부 접속을 허용해야 합니다.

1. 버킷 관리 화면의 상단 탭에서 **[Settings (설정)]** 메뉴를 클릭합니다.
2. 스크롤을 내려 **Public Access (공개 액세스)** 섹션을 찾습니다.
3. 가장 간편한 방식인 **[Public Development URL (r2.dev 하위 도메인)]** 항목의 **[Allow Access (액세스 허용)]** 버튼을 클릭합니다.
4. 경고창이 뜨면 확인을 위해 `allow`라고 직접 타이핑하고 승인합니다.
5. 이제 R2에서 생성된 전용 도메인 주소(예: `https://pub-xxxxx.r2.dev`)가 표시됩니다.
   * *참고: 본인 소유의 도메인이 있다면 [Custom Domains]에 등록하는 것이 향후 더 좋지만 테스트용으로는 이것으로 충분합니다.*

## 5단계: 대망의 "CORS" 설정하기 (가장 중요 ⭐️)

Vercel(내 웹사이트) 뷰어가 Cloudflare(창고)의 PDF를 가져와서 띄우려면, 창고 관리인에게 **"저기 Vercel 웹사이트에서 파일 달라고 요청하면 의심하지 말고 내어주세요!"** 라고 권한을 줘야 합니다. 이게 CORS 설정입니다.

1. 같은 **[Settings (설정)]** 화면에서 조금 더 스크롤을 내려 **CORS Policy (CORS 정책)** 항목을 찾습니다.
2. **[Add CORS policy (CORS 정책 추가)]** 버튼을 누릅니다.
3. 아래의 JSON 코드를 그대로 복사해서 붙여넣습니다:

```json
[
  {
    "AllowedOrigins": [
      "*"
    ],
    "AllowedMethods": [
      "GET",
      "HEAD"
    ],
    "AllowedHeaders": [
      "*"
    ],
    "ExposeHeaders": [
      "Accept-Ranges",
      "Content-Range",
      "Content-Length",
      "Content-Type",
      "ETag"
    ],
    "MaxAgeSeconds": 3000
  }
]
```
*(참고: `"AllowedOrigins": ["*"]` 는 현재 모든 웹사이트의 뷰어 요청을 허용한다는 뜻입니다. 나중에 실제 배포 후에는 `"*" ` 대신 `"https://koreantalk.vercel.app"` 만 넣으시면 철통 보안이 됩니다.)*

4. **[Save (저장)]**을 누릅니다.

---

## 6단계: 완성! 내 프로젝트에 적용하기

이제 모든 준비가 끝났습니다!

1. Cloudflare R2 버킷 화면('Objects' 탭)으로 돌아가서 아까 올린 PDF 파일을 클릭합니다.
2. 파일 상세정보에 뜨는 퍼블릭 URL을 복사합니다. (예: `https://pub-1234abcd.r2.dev/TOPIK_Exam_1.pdf`)
3. 앞서 우리가 개조한 뷰어 주소 뒤에 이렇게 붙여줍니다.

👉 `https://[선생님의_Vercel주소].vercel.app/pdf-viewer.html?url=https://pub-1234abcd.r2.dev/TOPIK_Exam_1.pdf&section=exam`

이제 선생님의 뷰어가 100MB PDF 파일을 Vercel 서버 비용 0원으로 1초 만에 불러옵니다!
