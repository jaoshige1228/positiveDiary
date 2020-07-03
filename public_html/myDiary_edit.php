<?php
require_once(__DIR__ . '/../config/config.php');
$date = new MyApp\Controller\Diary();
$serif = new MyApp\Controller\Serif();

$myDiaries = $date->showDiary($_GET['date'], $_SESSION['me']->id);

$date->myDiaryEdit($_GET['date'], $_SESSION['me']->id);

$serif->getSerif($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>美少女ポジティブ日記</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
  <header>
    <div class="cal_toggle">
      <div><a href="/"><img src="/icon/home.png"></a></div>
    </div>

    <div class="link">
      <div><a><img src="/icon/save.png" onclick="document.getElementById('diary_edit').submit()"></a></div>
    </div>
  </header>

  <div class="responsible">

    <section class="calender_wrap">
      <div class="yearMonth">
        <div class="ym"><span class="num"><?= h($date->year); ?></span>年<span class="num"><?= h($date->month); ?></span>月<span class="num"><?= h($date->day); ?></span>日(<span class="num"><?= h($date->youbi); ?></span>)</div>
      </div>

      <div class="myDiary_window">
        <form action="/myDiary_edit.php?date=<?= $_GET['date']; ?>" method="post" id="diary_edit">
          <div class="good_wrap">
            <div class="good_edit1">
              <input type="text" name="good_1" placeholder="良いこと１" value="<?= h($myDiaries['good_1']); ?>">
            </div>
            <div class="good_edit2">
              <input type="text" name="good_2" placeholder="良いこと２" value="<?= h($myDiaries['good_2']); ?>">
            </div>
            <div class="good_edit3">
              <input type="text" name="good_3" placeholder="良いこと３" value="<?= h($myDiaries['good_3']); ?>">
            </div>
          </div>
          <textarea name="other" id="" cols="30" rows="10" placeholder="その他、追記など"><?= h($myDiaries['other']); ?></textarea>
        </form>
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

</body>
</html>