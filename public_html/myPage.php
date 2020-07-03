<?php
require_once(__DIR__ . '/../config/config.php');
$users = new MyApp\Controller\User();
$serif = new MyApp\Controller\Serif();

$serif->getSerif($_SERVER['PHP_SELF']);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <div id="mask" class="hidden">

  </div>

  <div class="modal hidden">
    <div class="close_wrap"><span class="close">×<span></div>
    <h3>ログアウトしますか？</h3>
    <div class="logout" onclick="document.getElementById('logout').submit()">OK</div>
    <form action="/logout.php" method="post" id="logout">
      <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
    </form>
  </div>
  <header>
    <div class="cal_toggle">
      <div><a href="/"><img src="/icon/home.png"></a></div>
      <div><a class="logoutWindowButton"><img src="/icon/exit.png"></a></div>
    </div>

    <div class="link">
      <div><a href="/myPage_edit.php"><img src="/icon/myPage_edit.png"></a></div>
    </div>
  </header>

  <div class="responsible">

    <section class="calender_wrap">

      <div class="myIcon_wrap">
        <div class="myIcon">
          <img src="/thumbs/<?= $users->icon; ?>">
        </div>
      </div>

      <div class="myStatus_wrap">
        <div class="myStatus">
            ユーザー名：<?= $users->name; ?>
        </div>
        <div class="myStatus">
            日記をつけた日数：<?= $users->NoteDays; ?>日
        </div>
        <div class="myStatus">
            現在連続記録日数：<?= $users->KeepNoteDays; ?>日
        </div>
        <div class="myStatus">
            最高連続記録日数：<?= $users->HighScoreNoteDays; ?>日
        </div>
        <div class="myStatus">
            ララからの呼び方：<?= $users->Nickname; ?>
        </div>
        <div class="myStatus">
            ララの一人称：<?= $users->firstPerson; ?>
        </div>
      </div>
    </section> 
    
    <section class="girl_wrap">
      <div class="girl">
        <img src="/images/<?= h($serif->face); ?>" alt="" >
      </div>
      <div class="speechBubble_wrap">
        <div class="speechBubble"><?= nl2br(h($serif->serif)); ?></div>
      </div>
    </section>
      </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script>
    $(function(){
      $('.logoutWindowButton').on('click', function(){
        $('.modal').removeClass('hidden');
        $('#mask').removeClass('hidden');
      });

      $('.close').on('click', function(){
        $('.modal').addClass('hidden');
        $('#mask').addClass('hidden');
      });
    });
  </script>
</body>
</html>