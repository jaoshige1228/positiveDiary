<?php

namespace MyApp\Controller;

class Diary extends \MyApp\Controller {
  public $_thisDate;
  public $prevMore;
  public $nextMore;
  public $year;
  public $month;
  public $day;
  public $youbi;

  public function __construct(){
    // リンク用
    $this->_thisDate = $this->_createThisDate();
    $this->prevMore = $this->_createPrevMoreLink();
    $this->nextMore = $this->_createNextMoreLink();

    $this->dateSeparate($_GET['date']);
  }


  // 連続日記日数に関連する処理
  public function keepDays($id){
    $myDiary = new \MyApp\Model\DiaryModel();
    $users = new \MyApp\Model\User();
    // 日記の日付のみを全て取り出す
    $tempDiaryDates = $myDiary->getDiaryDates($id);
    // 最大日記記録数を取得
    $highScore = $users->getHighScore($id);

    // 日記を一日もつけてない場合は処理をスキップ
    if($tempDiaryDates){
      // 日記の総日数を格納
      $countDiary = count($tempDiaryDates);
      // 日付の後ろについている曜日を切り取る
      $diaryDates = $this->_validatediaryDates($tempDiaryDates);
      // 日付が連続かどうかを判定する
      $keepDays = $this->_checkContinuity($diaryDates);
      // 連続日記が最大連続日数を超えた場合更新
      if($keepDays > $highScore){
        $highScore = $keepDays;
      }
      // 諸々をデータベースに登録
      $users->updateDiaryData([
        'NoteDays' => $countDiary,
        'KeepNoteDays' => $keepDays,
        'HighScoreNoteDays' => $highScore,
        'id' => $id
      ]);
    }else{
      $keepDays = 0;
    }

    // return $countDiary;
  }
  
  private function _validatediaryDates($values){
    $res = [];
    foreach($values as $value){
      $res[] = substr($value , 0, 10);
    }
    return $res;
  }
  
  private function _checkContinuity($values){
    $keepDays = 1;
    // 日数の差が１なら連続カウント
    for($i = 0; $i < count($values) - 1; $i++){
      $time1 = new \DateTime($values[$i]);
      $time2 = new \DateTime($values[$i + 1]);

      $diff = $time1->diff($time2);
      if($diff->format('%d') == 1){
        $keepDays++;
      }else{
        break;
      }
    }
    return $keepDays;
  }


  // プレビュー画面にて日記一覧を取得する
  public function getDiaryList($date, $id){
    $myDiary = new \MyApp\Model\DiaryModel();
    // 該当する月の日記データを取得
    $tempDiaryData = $myDiary->getDiaryList($date, $id);
    // その月の日記がゼロの場合は空文字が返ってくるので、空文字かどうかを判定
    if(!empty($tempDiaryData)){
      // 空の日記を消す処理
      $tempDiaryList = $this->_eraseEmptyDiary($tempDiaryData);
      // 番号を降り直す
      $diaryList = array_values($tempDiaryList);
      return $diaryList;
    }else{
      return '';
    }
  }


  private function _eraseEmptyDiary($data){
    // 多次元配列の子要素が空の場合削除する（親要素は残る）
    foreach($data as $value){
      $res[] = array_filter($value, 'strlen');
    }
    // 配列の要素数が１（＝日付のデータしか入ってない）場合、その配列を消す
    $count = count($res);
    for($i = 0; $i < $count; $i++){
      if(count($res[$i]) == 1){
        unset($res[$i]);
      }
    }
    return $res;
  }

  // 日付から月と日と曜日を分ける
  private function _dateSeparateToList($date){
    $tempMonth = substr($date, 5, 2);
    $month = (substr($tempMonth, 0, 1) == 0) ? substr($tempMonth, 1): $tempMonth;
    $tempDay = substr($date, 8, 2);
    $day = (substr($tempDay, 0, 1) == 0) ? substr($tempDay, 1): $tempDay;
    $tempYoubi = substr($date, 11);
    $youbi = $this->_translateYoubi($tempYoubi);

    return [$month, $day, $youbi];
  }

  public function showDiaryList($diary){
    // 空白の日記がある場合は空文字をセットし、文字数が一定以上になる場合は後半を省く
    $good_1 = $this->_emptyCheckAndCut((isset($diary['good_1'])) ? $diary['good_1'] : '');
    $good_2 = $this->_emptyCheckAndCut((isset($diary['good_2'])) ? $diary['good_2'] : '');
    $good_3 = $this->_emptyCheckAndCut((isset($diary['good_3'])) ? $diary['good_3'] : '');
    list($month, $day, $youbi) = $this->_dateSeparateToList($diary['date']);
    $body = sprintf('
      <div class="diary_list">
        <a href="/myDiary.php?date=%s">
          <div class="diary_list_date">
            %s月%s日（%s）
          </div>
          <div class="diary_list_article">
            <ul>
              <li>%s</li>
              <li>%s</li>
              <li>%s</li>
            </ul>
          </div>
        </a>
      </div>
    ', $diary['date'], $month, $day, $youbi, $good_1, $good_2, $good_3);

    return $body;
  }

  // 日記が指定の文字数以上ならカットする
  private function _emptyCheckAndCut($goodData){
    $good = (isset($goodData)) ? $goodData : '';
    $strLimit = 16;
    if(mb_strlen($good) >= $strLimit){
      $good = mb_substr($good, 0, $strLimit) . '<span class="fade">……</span>';  
    }
    return $good;
  }

  // 日記編集
  public function myDiaryEdit($date, $id){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $myDiary = new \MyApp\Model\DiaryModel();
      // 日記が空っぽならデータベース登録を行わず、すでに日記があるなら消去する
      if($_POST['good_1'] == ''
      && $_POST['good_2'] == ''
      && $_POST['good_3'] == ''
      && $_POST['other'] == ''){
        $myDiary->deleteMyDiary([
          'date' => $date,
          'id' => $id
        ]);
      }else{
        $myDiary->createOrUpdateMyDiary([
          'good_1' => $_POST['good_1'],
          'good_2' => $_POST['good_2'],
          'good_3' => $_POST['good_3'],
          'other' => $_POST['other'],
          'date' => $date,
          'id' => $id
        ]);
      }
      // 連続日記日数に関連する処理
      $this->keepDays($id);
      
      header('Location: ' . SITE_URL. '/myDiary.php?date='. $date);
    }
  }

  // 前日のリンク作成
  private function _createPrevMoreLink(){
    $dt = clone $this->_thisDate;
    $tempDt =  $dt->modify('-1 day')->format('Y-m-d');
    $youbi = new \DateTime($tempDt);
    $date = $tempDt. '-'. $youbi->format('D');
    return $date;
  }

  // 次の日のリンク作成
  private function _createNextMoreLink(){
    $dt = clone $this->_thisDate;
    $tempDt =  $dt->modify('+1 day')->format('Y-m-d');
    $youbi = new \DateTime($tempDt);
    $date = $tempDt. '-'. $youbi->format('D');
    return $date;
  }

  // 表示されてる日のオブジェクトを作成
  private function _createThisDate(){
    $tempDate = substr($_GET['date'], 0, 10);
    $thisDate = new \DateTime($tempDate);
    return $thisDate;
  }

  // 日記を表示
  public function showDiary($date, $id){
    $myDiary = new \MyApp\Model\DiaryModel();
    return $myDiary->showDiary($date, $id);
  }

  // パラメータから取得した日付を分割する
  public function dateSeparate($date){
    $this->year = substr($date, 0, 4);
    $tempMonth = substr($date, 5, 2);
    $this->month = (substr($tempMonth, 0, 1) == 0) ? substr($tempMonth, 1): $tempMonth;
    $tempDay = substr($date, 8, 2);
    $this->day = (substr($tempDay, 0, 1) == 0) ? substr($tempDay, 1): $tempDay;
    $tempYoubi = substr($date, 11);
    $this->youbi = $this->_translateYoubi($tempYoubi);
  }

  // 曜日の簡略表現を日本語に直す
  private function _translateYoubi($tempYoubi){
    switch($tempYoubi){
      case 'Sun': 
        return '日';
      case 'Mon': 
        return '月';
      case 'Tue': 
        return '火';
      case 'Wed': 
        return '水';
      case 'Thu': 
        return '木';
      case 'Fri': 
        return '金';
      case 'Sat': 
        return '土';
      default:
        return 'エラー';
    };
  }

}