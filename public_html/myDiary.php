<?php
require_once(__DIR__ . '/../config/config.php');
$date = new MyApp\Controller\Diary();
$serif = new MyApp\Controller\Serif();

$myDiaries = $date->showDiary($_GET['date'], $_SESSION['me']->id);
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
      <div><a href="/myDiary_edit.php?date=<?= $_GET['date']; ?>"><img src="/icon/edit.png"></a></div>
    </div>
  </header>

  <div class="responsible">

    <section class="calender_wrap">
      <div class="yearMonth">
        <div><a href="/myDiary.php?date=<?= h($date->prevMore); ?>">◁</a></div>
        <div class="ym"><span class="num"><?= h($date->year); ?></span>年<span class="num"><?= h($date->month); ?></span>月<span class="num"><?= h($date->day); ?></span>日(<span class="num"><?= h($date->youbi); ?></span>)</div>
        <div><a href="/myDiary.php?date=<?= h($date->nextMore); ?>">▷</a></div>
      </div>

      <div class="myDiary_window">
        <div class="good_wrap">
          <div class="good1">
            <?= h($myDiaries['good_1']); ?>
          </div>
          <div class="good2">
            <?= h($myDiaries['good_2']); ?>
          </div>
          <div class="good3">
            <?= h($myDiaries['good_3']); ?>
          </div>
        </div>
        <div class="supple">
          <?= nl2br(h($myDiaries['other'])); ?>
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

</body>
</html>