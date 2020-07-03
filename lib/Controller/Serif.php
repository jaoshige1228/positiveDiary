<?php

namespace MyApp\Controller;

class Serif extends \MyApp\Controller {
  public $serif;
  public $genre;
  public $key1;
  public $key2;
  public $key3;
  public $face;
  public $persons;

  public function getSerif($place){
    // 現在のページ名ごとに処理を変える
    $this->_validateCurrentPage($place);
  }

  private function _validateCurrentPage($place){
    switch($place){
      case '/index.php':
        $this->genre = 'トップページ';
        $tempSerifAndFace = $this->_topPageSerif();
        break;
      case '/index_list.php':
        $this->genre = 'トップページ';
        $tempSerifAndFace = $this->_topPageSerif();
        break;
      case '/myDiary.php':
        $this->genre = '日記閲覧ページ';
        $tempSerifAndFace = $this->_myDiarySerif();
        break;
      case '/myDiary_edit.php':
        $this->genre = '日記編集ページ';
        $tempSerifAndFace = $this->_myDiaryEditSerif();
        break;
      case '/myPage.php':
        $this->genre = 'マイページ';
        $tempSerifAndFace = $this->_MyPageSerif();
        break;
      case '/myPage_edit.php':
        $this->genre = 'マイページ編集ページ';
        $tempSerifAndFace = $this->_MyPageEditSerif();
        break;

      default :
        $this->genre = 'その他';
        break;
    }

    // 複数種類あるセリフから一つのみ選別
    $tempSerifAndFace = $this->_chooseSerifAndFase($tempSerifAndFace);

    // ユーザーデータベースから一人称と二人称を取得し、取得したセリフの<一人称> ＜二人称＞を置き換える
    $this->serif = $this->_validateSerif($tempSerifAndFace['serif']);
    $this->face = $tempSerifAndFace['face'];
  }


  // 日記閲覧画面処理
  private function _myDiarySerif(){
    $serifData = new \MyApp\Model\SerifModel();

    // 今日日記を書いたかどうかチェック
    // 未来かどうかもチェック
    $this->key1 = $this->_checkTodayDiary($_GET['date']);
    if($this->key1 == 'notToday'){
      // 今日じゃなかった時の処理
      return $serifData->getSerifNoKey($this->genre);
    }else if($this->key1 == 'doneEdit'){
      // 今日かつ日記をつけていた時の処理
      // 日記の日数によるセリフ変化を識別
      list($keepDays, $allDays) = $this->_getDiaryDaysData();
      $this->key2 = $this->_diaryDaysCheck($keepDays, $allDays);
      
      if($this->key2){
        return $serifData->getSerifTwoKey($this->genre, $this->key1, $this->key2);
      }else{
        return $serifData->getSerifOneKey($this->genre, $this->key1);
      }
    }else if($this->key1 == 'yetEdit' || $this->key1 == 'future'){
      // 今日かつ日記をつけていない時の処理
      return $serifData->getSerifOneKey($this->genre, $this->key1);
    }
  }
  // 連続でどれほど日記をつけたのかチェック
  private function _getDiaryDaysData(){
    $userData = new \MyApp\Model\User();
    // データベースから、日記の記録を取得
    $diaryData = $userData->GetMyData($_SESSION['me']->id);
    $keepDays = $diaryData['KeepNoteDays'];
    $allDays = $diaryData['NoteDays'];
    return [$keepDays, $allDays];
  }

  private function _diaryDaysCheck($keepDays, $allDays){
    switch($keepDays){
      case 3:
        return $keepDays;
        break;
      case 7:
        return $keepDays;
        break;
      default:
        // 総記録数が１日＝初めて日記を書いた時に特別処理
        switch($allDays){
          case 1:
            return $allDays;
            break;
          default:
            return false;
            break;
        }
      break;
    }
  }

  private function _checkTodayDiary($date){
    // 今日の日付と今見てる日記の日付が同じだった場合スイッチオン
    $diaryDate = $this->_validatediaryDates($date);
    $today = new \DateTime('today');
    $today = $today->format('Y-m-d');
    if($today == $diaryDate){
      $myDiary = new \MyApp\Model\DiaryModel();
      // その日の日記が存在するか否かをチェック
      if($myDiary->issetTodayDiary($diaryDate)){
        $res = 'doneEdit';
      }else{
        $res = 'yetEdit';
      };
    }else if(strtotime($today) < strtotime($diaryDate)){
      $res = 'future';
    }else{
      $res = 'notToday';
    }

    return $res;
  }


  // 日記編集画面処理
  private function _myDiaryEditSerif(){
    $this->key1 = $this->_checkTodayDiary($_GET['date']);
    if($this->key1 != 'notToday'){
        $this->key1 = 'today';
    }
    $serifData = new \MyApp\Model\SerifModel();
    return $serifData->getSerifOneKey($this->genre, $this->key1);
  }

  // マイページ処理
  private function _MyPageSerif(){
    $serifData = new \MyApp\Model\SerifModel();
    list($keepDays, $allDays) = $this->_getDiaryDaysData();
    $this->key1 = $this->_diaryDaysCheckMyPage($keepDays, $allDays);
    if($this->key1){
      return $serifData->getSerifOneKey($this->genre, $this->key1);
    }else{
      return $serifData->getSerifNoKey($this->genre);
    }
  }
  private function _diaryDaysCheckMyPage($keepDays, $allDays){
    switch($allDays){
      case 3:
        $res = $allDays;
        break;
      case 5:
        $res = $allDays;
        break;
      case 10:
        $res = $allDays;
        break;
      default:
        $res = false;
        break;
    }

    return $res;
  }

  // マイページ編集ページ処理
  private function _MyPageEditSerif(){
    $serifData = new \MyApp\Model\SerifModel();
    return $serifData->getSerifNoKey($this->genre);
  }

  // トップページ処理
  private function _topPageSerif(){
    $serifData = new \MyApp\Model\SerifModel();
    // 現在時刻を取得
    $now = date('H');
    // 現在時刻ごとに朝昼夜を分ける
    if($now >= 04 && $now <= 9){
      $this->key1 = '朝';
    }else if($now >= 10 && $now <= 17){
      $this->key1 = '昼';
    }else{
      $this->key1 = '夜';
    }
    // ジャンル「トップページ」、キー「時間帯」を使って台詞を検索し、リターン
    return $serifData->getSerifOneKey($this->genre, $this->key1);
  }



  // 台詞に一人称と二人称を設定し、顔アイコンも設定
  private function _validateSerif($tempSerif){
    $userData = new \MyApp\Model\User();
    $persons = $userData->getFpSp($_SESSION['me']->id);
    return str_replace(["＜一人称＞", "＜二人称＞"] , [$persons['firstPerson'], $persons['Nickname']], $tempSerif);
  }

  // 複数種類あるセリフから一つのみ選別
  private function _chooseSerifAndFase($tempSerifAndFace){
    $i = mt_rand(0, count($tempSerifAndFace) - 1);
    return $tempSerifAndFace[$i];
  }


  private function _validatediaryDates($date){
    return substr($date , 0, 10);
  }

}