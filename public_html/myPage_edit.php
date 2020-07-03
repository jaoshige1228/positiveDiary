<?php
require_once(__DIR__ . '/../config/config.php');
$users = new MyApp\Controller\User();
$uploader = new MyApp\Controller\ImageUploader();
$serif = new MyApp\Controller\Serif();

$serif->getSerif($_SERVER['PHP_SELF']);
$uploader->upload($_SESSION['me']->id);
$users->updateMyData($_SESSION['me']->id);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ編集</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <header>
    <div class="cal_toggle">
      <div><a href="/"><img src="/icon/home.png"></a></div>
    </div>

    <div class="link">
      <div><a><img src="/icon/save.png" onclick="document.getElementById('updateMyData').submit()"></a></div>
    </div>
  </header>

  <div class="responsible">

    <section class="calender_wrap">

      <div class="myIcon_wrap">
        <div class="myIcon">
          <img src="/thumbs/<?= $users->icon; ?>" alt="モニカのアイコンです">
        </div>
        <div class="button_iconChange">
          <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE; ?>">
            <input type="hidden" name="key" value="upload">
            <input type="file" name="image">
            <input type="submit" value="変更">
          </form>
        </div>
      </div>

      <div class="myStatus_wrap">
        <form action="/myPage_edit.php" method="post" id="updateMyData">
          <div class="myStatus">
              ユーザー名：<input type="text" name="name" value="<?= $users->name; ?>">
          </div>
          <div class="myStatus">
              ララからの呼び方：<input type="text" name="Nickname" value="<?= $users->Nickname; ?>">
          </div>
          <div class="myStatus">
              ララの一人称：<input type="text" name="firstPerson" value="<?= $users->firstPerson; ?>">
          </div>
          <input type="hidden" name="key" value="myData">
        </form>
      </div>
    </section> 
    
    <section class="girl_wrap">
      <div class="girl">
        <a href="/">
          <img src="/images/<?= $serif->face; ?>" alt="" >
        </a>
      </div>
      <div class="speechBubble_wrap">
        <div class="speechBubble"><?= $serif->serif?></div>
      </div>
    </section>
      </div>
  </div>

</body>
</html>