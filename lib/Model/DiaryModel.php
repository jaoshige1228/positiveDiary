<?php

namespace MyApp\Model;

class DiaryModel extends \MyApp\Model {

  public function issetTodayDiary($diaryDate){
    $sql = "select id from myDiary where date like :date";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':date', $diaryDate . '%', \PDO::PARAM_STR);
    $stmt->execute();
    return  $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }

  public function getDiaryDates($id){
    $sql = "select date from myDiary where userId = :id order by date desc";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':id',$id,\PDO::PARAM_INT);
    $stmt->execute();
    return  $stmt->fetchAll(\PDO::FETCH_COLUMN);
    // $temp = $stmt->fetch();
  }


  // 日記が空っぽなら削除
  public function deleteMyDiary($values){
    $sql = "delete from myDiary where id = (select id from
    (select id from myDiary where userId = :id and date = :date) as temp)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $values['id'],
      ':date' => $values['date']
    ]);
  }

  // 該当する月の日記を全て取得
  public function getDiaryList($date, $id){
    $sql = "select good_1, good_2, good_3, date from myDiary where userId = :id and date like :date order by date desc";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':date',$date.'%',\PDO::PARAM_STR);
    $stmt->bindvalue(':id',$id,\PDO::PARAM_INT);
    $stmt->execute();
    $temp = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // その月の日記を完全につけていない（＝データが一つもない）場合、空文字を返す
    if($temp == false){
      $res =  '';
    }else{
      return $temp;
    }
  }

  // 日記がすでに存在しているかどうかを調べる
  public function filledCheck($date, $id){
    $existCheck = "select good_1, good_2, good_3, other from myDiary where userId = :id and date = :date";
    $stmt = $this->db->prepare($existCheck);
    $stmt->execute([
      ':id' => $id,
      ':date' => $date
    ]);

    // 日記がない、もしくはあるけど全部空っぽの場合に空文字を返す
    $check = $stmt->fetch(\PDO::FETCH_ASSOC);
    // var_dump($check);
    if($check === false){
      return '';
    }else if(!$this->_emptyCheck($check)){
      return '';
    }
    else{
      return '<span class="filled">●</span>';
    }
  }

  private function _emptyCheck($check){
    foreach($check as $value){
      if($value !== ''){
        $res = true;
        break;
      }else{
        $res = false;
      }
    }
    return $res;
  }

  // 記録しようとしてる日記がすでに存在するかどうかを確かめる
  public function createOrUpdateMyDiary($values){
    $existCheck = "select id from myDiary where userId = :id and date = :date";
    $stmt = $this->db->prepare($existCheck);
    $stmt->execute([
      ':id' => $values['id'],
      ':date' => $values['date']
    ]);
    if($stmt->fetch() === false){
      $this->_createMyDiary($values);
    }else{
      $this->_updateMyDiary($values);
    }
  }

  private function _updateMyDiary($values){
    $sql = "update myDiary set 
    good_1 = :good_1,
    good_2 = :good_2,
    good_3 = :good_3,
    other = :other 
    where userId = :id 
    and date = :date
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':good_1' => $values['good_1'],
      ':good_2' => $values['good_2'],
      ':good_3' => $values['good_3'],
      ':other' => $values['other'],
      ':id' => $values['id'],
      ':date' => $values['date']
    ]);
  }

  private function _createMyDiary($values){
    $sql = "insert into myDiary(good_1, good_2, good_3, other, userId, date) values(:good_1, :good_2, :good_3, :other, :userId, :date)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':good_1' => $values['good_1'],
      ':good_2' => $values['good_2'],
      ':good_3' => $values['good_3'],
      ':other' => $values['other'],
      ':userId' => $values['id'],
      ':date' => $values['date']
    ]);
  }

  public function showDiary($date, $id){
    //日付とidで日記を検索し、取得
    $sql = "select good_1, good_2, good_3, other from myDiary where userId = :id and date = :date";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':date',$date,\PDO::PARAM_STR);
    $stmt->bindvalue(':id',$id,\PDO::PARAM_INT);
    $stmt->execute();

   //取得に失敗したら配列を全て空に。成功したらそのまま返す
    $res = $stmt->fetch(\PDO::FETCH_ASSOC);
    if($res === false){
      $res = array_fill(0, 4, '');
    }else{
      return $res;
    }
  }

  // サンプル用日記作成
  public function createSampleDiary($id, $one, $two, $three, $four){
    $sql = "insert into myDiary(userId, good_1, good_2, good_3, other, date)
    values(:id, '朝ごはんが美味しかった', '空が晴れていた', '目覚めがスッキリだった', '', :date)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
      ':date' => $one
    ]);

    $sql = "insert into myDiary(userId, good_1, good_2, good_3, other, date)
    values(:id, '髪型が良い感じにキマッた', 'コンビニの会計が777円だった', '', 'カラオケにいきたいな〜', :date)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
      ':date' => $two
    ]);

    $sql = "insert into myDiary(userId, good_1, good_2, good_3, other, date)
    values(:id, 'あの漫画の最新巻が発売された', '宝くじ１万円が当たった', '新規契約を２件取ることができた', '', :date)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
      ':date' => $three
    ]);

    $sql = "insert into myDiary(userId, good_1, good_2, good_3, other, date)
    values(:id, '先輩に焼肉を奢ってもらった', '部屋の掃除をしたらスッキリした', '', '', :date)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
      ':date' => $four
    ]);
  }
}