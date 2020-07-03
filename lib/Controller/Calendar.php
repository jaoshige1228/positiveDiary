<?php

namespace MyApp\Controller;

class Calendar extends \MyApp\Controller {
  // カレンダー表示画面の矢印リンク用
  public $prev;
  public $next;

  // カレンダーの上に表示する日付用
  public $year;
  public $month;
  public $day;
  public $youbi;
  public $yearMonth;
  private $_thisMonth;
  
  // 初期画面のgetが無い時用
  public $GetYearMonth;
  public $GetToday;
  public $GetTodayYM;

  // カレンダー表示用
  private $_period;
  private $_body;
  private $_head;
  private $_tail;


  public function __construct(){
    $this->_thisMonth = $this->_createThisMonth();
    $this->yearMonth = $this->_thisMonth->format('F Y');
    $this->GetYearMonth = $this->_thisMonth->format('Y-m');
    $tempToday =  new \DateTime('today');
    $this->GetToday = $tempToday->format('Y-m-d-D');
    $this->GetTodayYM = $tempToday->format('Y-m');

    // カレンダーの上に表示する日付用
    $this->prev = $this->_createPrevLink();
    $this->next = $this->_createNextLink();

    // 矢印リンク用の前月・翌月部分を作成
    $this->year = $this->_thisMonth->format('Y');
    $this->month = $this->_thisMonth->format('n');
    $this->day = $this->_thisMonth->format('d');
    $this->youbi = $this->_thisMonth->format('w');

    // カレンダー表示用
    $this->_period = $this->_createPeriod();
  }

  // プレビュー表示にて、GETがセットされていない場合は今日の年月を挿入
  public function getGetDate(){
    if(!isset($_GET['date'])){
      $_GET['date'] = $this->GetTodayYM;
    }
  }


  private function _createPeriod(){
    // 今月の1日から末日までのデータを作成
    $period = new \DatePeriod(
      new \DateTime('first day of '. $this->yearMonth),
      new \DateInterval('P1D'),
      new \DateTime('first day of '. $this->yearMonth. '+1 month')
    );
    return $period;
  }

  public function showCalendar(){
    $this->_body = $this->_getBody();
    $this->_tail = $this->_getTail();
    $this->_head = $this->_getHead();

    echo $this->_tail. $this->_body. $this->_head;
  }

  private function _getBody(){
    $filledCheck = new \MyApp\Model\DiaryModel();
    $id = $_SESSION['me']->id;

    $body = '';
    // 今日の日付を作成
    $today = new \DateTime('today');

    // 今月の1日から末日までを繰り返し表示
    foreach ($this->_period as $day){
      if($day->format('w') == 0){
        $body .= '</tr><tr>';
      }
      $todayClass = ($day->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : '';
      $date = $day->format('Y-m-d-D');
      // その日に日記があるかどうかを調べる
      $filled = $filledCheck->filledCheck($date, $id);
      $body .= sprintf('<td><a href="/myDiary.php?date=%s" class="youbi_%d %s">%d%s</a></td>', $date, $day->format('w'), $todayClass, $day->format('d'), $filled);
    }
    return $body;
  }
  private function _getHead(){
    $head = '';
    // 翌月部分
    $firstDayofNextMonth = new \DateTime('first day of '. $this->yearMonth. '+1 month');
    while($firstDayofNextMonth->format('w') > 0){
      $head .= sprintf('<td class="gray">%d</td>', $firstDayofNextMonth->format('d'));
      $firstDayofNextMonth->add(new \DateInterval('P1D'));
    }
    return $head;
  }
  private function _getTail(){
    $tail = '';
    // 前月部分
    $lastDayofNextMonth = new \DateTime('last day of '. $this->yearMonth. '-1 month');
    while($lastDayofNextMonth->format('w') < 6){
      $tail = sprintf('<td class="gray">%d</td>', $lastDayofNextMonth->format('d')). $tail;
      $lastDayofNextMonth->sub(new \DateInterval('P1D'));
    }
    return $tail;
  }

  private function _createThisMonth(){
    // パラメータから今月のdateを取得
    try{
      if(!isset($_GET['date']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['date'])){
        throw new \Exception();
      }
      $thisMonth = new \DateTime($_GET['date']);
    }catch(\Exception $e){
      $thisMonth = new \DateTime('first day of this month');
    }
    return $thisMonth;
  }

  private function _createPrevLink(){
    $dt = clone $this->_thisMonth;
    return $dt->modify('-1 month')->format('Y-m');
  }
  private function _createNextLink(){
    $dt = clone $this->_thisMonth;
    return $dt->modify('+1 month')->format('Y-m');
  }
}