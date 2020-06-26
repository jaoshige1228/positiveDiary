<?php
require_once(__DIR__ . '/../config/config.php');
$signup = new MyApp\Controller\Signup();

$signup->run();

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>美少女ポジティブ日記</title>
  <link rel="stylesheet" href="/styles_top.css">
</head>
<body>
  <div class="easyLogin">
    <form action="/top.php" method="post" id="easyLogin">
      <span onclick="document.getElementById('easyLogin').submit();">かんたんログイン</span>
      <input type="hidden" name="key" value="easyLogin">
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>
  </div>
  <input type="hidden" value="<?php echo $signup->errMode; ?>" id="signup_error_check">
  <header>
    <div class="header_content">
      <h1>ほめティブ日記</h1>
      <div class="catchphrase">幸せは、日常の中にある</div>
      <div class="buttons">
        <div class="button_open" id="login_window">ログイン</div>
        <div class="button_open" id="signup_window">新規登録</div>
      </div>
    </div>
  </header>
  
  <div id="mask" class="hidden">

  </div>

  <div class="modal hidden login">
    <div class="close_wrap"><span class="close">×<span></div>
    <h3>ログイン</h3>
    <div class="input_wrap">
      <form action="/top.php" method="post" id="login">
        <input type="text" name="email" placeholder="メールアドレス" value="<?= isset($signup->getValues()->login_email) ? h($signup->getValues()->login_email) : ''; ?>">
        <input type="password" name="password" placeholder="パスワード">
        <p class="err"><?= h($signup->getErrors('login')); ?></p>
        <!-- ログインか新規登録かを識別するキー -->
        <input type="hidden" name="key" value="login">
        <!-- トークン -->
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </form>
    </div>
    <div class="button_submit" id="loginButton" onclick="document.getElementById('login').submit();">
      ログイン
    </div>
  </div>
  <div class="modal hidden signup">
    <div class="close_wrap"><span class="close">×<span></div>
    <h3>新規登録</h3>
    <form action="/top.php" method="post" id="signup">
      <div class="input_wrap">
        <input type="text" name="name" placeholder="ユーザー名(後から変更可能)" value="<?= isset($signup->getValues()->name) ? h($signup->getValues()->name) : ''; ?>">
        <p class="err"><?= h($signup->getErrors('name')); ?></p>
        <input type="text" name="email" placeholder="メールアドレス" value="<?= isset($signup->getValues()->email) ? h($signup->getValues()->email) : ''; ?>">
        <p class="err"><?= h($signup->getErrors('email')); ?></p>
        <input type="password" name="password" placeholder="パスワード">
        <input type="password" name="password_confirm" placeholder="パスワード(確認)">
        <p class="err"><?= h($signup->getErrors('password')); ?></p>
        <!-- ログインか新規登録かを識別するキー -->
        <input type="hidden" name="key" value="signup">
        <!-- トークン -->
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </div>
      <div class="button_submit" id="signupButton" onclick="document.getElementById('signup').submit();">
        新規登録
      </div>
    </form>
  </div>

  <main>
    <section class="about1">
      <img src="/images/greenSky.jpg" alt="特徴1">
      <div>
        <h3>毎日の「ちょっぴり幸せ」を記録</h3>
        <p>お昼ご飯が美味しかった。<br>久しぶりに友達に会った。<br>一日中天気が良かった。<br>笑える出来事があった。<br>あなたの日常は、小さな幸せに満ちています。<br>つい、見落としがちな幸せを<br>記録に留めてみませんか？</p>
        <div class="clover_image">
          <img src="/images/clover.png">
        </div>
      </div>
    </section>

    <section class="about2">
      <img src="/images/greenSky.jpg" alt="特徴2">
      <div>
        <h3>あなたを褒める美少女</h3>
        <p>あなたは、立派です。<br>日々を懸命に生き<br>その軌跡を記録しようとしているのですから。<br>そんな成果を、「ララ」は認めてくれます。<br>日記を続けることで、<br>毎日、ララがあなたをほめてくれます。</p>
        <div class="clover_image">
          <img src="/images/clover.png">
        </div>
      </div>
    </section>

    <section class="about3">
      <img src="/images/greenSky.jpg" alt="特徴3">
      <div>
        <h3>あなただけのララ</h3>
        <p>ララは、あなたの望む呼び方で<br>あなたのことを呼んでくれます。<br>あなた好みに、<br>彼女をデザインしましょう。</p>
        <div class="clover_image">
          <img src="/images/clover.png">
        </div>
      </div>
    </section>
  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script>
    $(function(){
      $('#login_window').on('click', function(){
        $('.login').removeClass('hidden');
        $('#mask').removeClass('hidden');
      });
      $('#signup_window').on('click', function(){
        $('.signup').removeClass('hidden');
        $('#mask').removeClass('hidden');
      });

      $('.close').on('click', function(){
        $('.modal').addClass('hidden');
        $('#mask').addClass('hidden');
      });
    });

    $(document).ready(function(){
      let check = $('#signup_error_check').val();
      // ログインか新規登録がエラーの場合、ウィンドウを開いておく
      if(check == 'signUpError'){
        $('.signup').removeClass('hidden');
        $('#mask').removeClass('hidden');
      }
      if(check == 'loginError'){
        $('.login').removeClass('hidden');
        $('#mask').removeClass('hidden');
      }
    });
  </script>
</body>
</html>