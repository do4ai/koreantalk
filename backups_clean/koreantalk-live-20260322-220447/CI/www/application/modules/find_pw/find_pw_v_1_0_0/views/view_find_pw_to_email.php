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
        <p style="font-size:26px; font-weight: bold; font-family: 'NotoSansKR-Bold'; letter-spacing: -1.3px;margin:60px 0 30px 0;"><?=lang('lang_10141','계정 비밀번호 재설정 요청')?></p>
        <div style="background:#fff; border-radius: 10px; padding:40px; box-sizing:border-box;">
            <p style="letter-spacing: -0.5px;color:#333; font-size:15px; line-height:26px;margin:0;padding:0">
            <?=$data['member_name']?><?=lang('lang_10142','님, 안녕하세요.')?><br><?=lang('lang_10143','계정 비밀번호 재설정 안내 메일입니다.')?>
          </p> 
          <p style="height:20px"></p>
          
          <p style="letter-spacing: -0.5px;color:#333; font-size:15px; line-height:26px;margin:0;padding:0"> 
            <?=lang('lang_10144','비밀번호 변경을 원하신다면, 하단의 버튼을 클릭 후 비밀번호 재설정을 진행해주세요.')?>
          </p>
          <p style="height:20px"></p>  
            <p style="letter-spacing: -0.5px;color:#333; font-size:15px; line-height:26px;margin:0;padding:0">  
            <?=lang('lang_10145','만약 비밀번호 재설정을 요청하지 않으셨다면, 보안을 위해 kiwijts123@gmail.com으로 문의해주시길 바랍니다.')?>
          </p>
          <p style="height:20px"></p> 
          <p style="letter-spacing: -0.5px;color:#333; font-size:15px; line-height:26px;margin:0;padding:0">
            <?=lang('lang_10146','비밀번호 변경 버튼을 클릭하여 비밀번호를 재설정하기 전에는 계정의 비밀번호는 변경되지 않습니다.')?>
          </p>
          <div style="text-align:center;margin: 0 auto; max-width:260px; margin-top:60px;">
            <a href="<?=PW_DOMAIN?>/find_pw_to_email/member_pw_change_key_check?p_code=<?=$data['change_pw_key']?>" style="padding: 13px 0; text-decoration: none; display: block; background:#DA3931;border-radius:24px; color:#fff; text-align:center;"><?=lang('lang_10147','비밀번호 변경하기')?></a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
