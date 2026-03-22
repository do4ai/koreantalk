<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/images/favicon.png">
    <title><?=SERVICE_NAME?></title>
    <style>
    </style>
  </head>
  <body style="margin:0;padding:0;max-width: 100%;background-color: #F9F9F9;">
    <div style="width:100%; height:100%;min-height: calc(100vh - 410px); margin: 0 auto;background-color: #F9F9F9;">
      <div style="max-width:600px; height:auto; margin: 0 auto; padding: 30px 20px 60px 20px; box-sizing: border-box;background-color: #F9F9F9;">
        <header>
          <img src="<?=THIS_DOMAIN?>/images/logo.png" alt="<?=SERVICE_NAME?>" style="width:170px;">
        </header>
        <p style="font-size:26px; font-weight: bold; font-family: 'NotoSansKR-Bold'; letter-spacing: -1.3px;margin:60px 0 30px 0;"><?=lang('lang_10108','이메일 인증 요청')?></p>
        <div style="background:#fff; border-radius: 10px; padding:40px; box-sizing:border-box;">
            <p style="letter-spacing: -0.5px;color:#333; font-size:15px; line-height:26px;margin:0;padding:0">
              <?=lang('lang_10109','인증 코드는')?> <?=$data['verify_num']?> <?=lang('lang_10110','입니다.')?>
          </p> 

        </div>
      </div>
    </div>
  </body>
</html>
