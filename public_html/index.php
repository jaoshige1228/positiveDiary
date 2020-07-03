<?php
require_once(__DIR__ . '/../config/config.php');
// $app = new MyApp\Controller\Index();
$cal = new MyApp\Controller\Calendar();
$serif = new MyApp\Controller\Serif();

// $app->run();
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
      <div><a href="/index_list.php?date=<?= $cal->GetYearMonth; ?>"><img src="/icon/article.png"></a></div>
    </div>

    <div class="link">
      <div><a href="/myDiary_edit.php?date=<?= $cal->GetToday; ?>"><img src="/icon/edit.png"></a></div>
      <div><a href="/myPage.php"><img src="/icon/myPage.png"></a></div>
    </div>
  </header>

  <div class="responsible">

    <section class="calender_wrap">
      <div class="yearMonth">
        <div><a href="/?date=<?= h($cal->prev); ?>">◁</a></div>
        <div class="ym"><span class="num"><?= h($cal->year); ?></span>年<span class="num"><?= h($cal->month); ?></span>月</div>
        <div><a href="/?date=<?= h($cal->next); ?>">▷</a></div>
      </div>
      <div class="main_calendar">
        <table>
          <tr class="Day">
            <th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th>
          </tr>
          <tr class="firstTr">
          <?= $cal->showCalendar(); ?>
          </tr>
        </table>
      </div>
    </section> 
  
    <section class="girl_wrap">
      <div class="girl">
        <a href="/">
          <img src="/images/<?= h($serif->face); ?>" alt="" >
        </a>
      </div>
      <div class="speechBubble_wrap">
        <div class="speechBubble"><?= nl2br(h($serif->serif)); ?></div>
      </div>
    </section>
  </div>

</body>
</html>