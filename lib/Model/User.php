<?php

namespace MyApp\Model;

class User extends \MyApp\Model {
  // 一人称と二人称を取得
  public function getFpSp($id){
    $sql = "select firstPerson, Nickname from users where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':id',$id,\PDO::PARAM_INT);
    $stmt->execute();
    return  $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function updateDiaryData($values){
    $sql = "update users set 
    NoteDays = :NoteDays,
    KeepNoteDays = :KeepNoteDays,
    HighScoreNoteDays = :HighScoreNoteDays
    where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':NoteDays' => $values['NoteDays'],
      ':KeepNoteDays' => $values['KeepNoteDays'],
      ':HighScoreNoteDays' => $values['HighScoreNoteDays'],
      ':id' => $values['id']
    ]);
  }
  
  public function getHighScore($id){
    $sql = "select HighScoreNoteDays from users where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id
    ]);
    return $stmt->fetch(\PDO::FETCH_COLUMN);
  }

  public function _saveIconPath($id, $fileName){
    $sql = "update users set icon = :fileName where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':fileName' => $fileName,
      ':id' => $id
    ]);
  }
  public function updateMydata($values){
    $sql = "update users set 
    name = :name,
    Nickname = :Nickname,
    firstPerson = :firstPerson
    where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':name' => $values['name'],
      ':Nickname' => $values['Nickname'],
      ':firstPerson' => $values['firstPerson'],
      ':id' => $values['id']
    ]);
  }

  public function GetMyData($id){
    $sql = "select * from users where id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create($values) {
    $stmt = $this->db->prepare("insert into users (name, Nickname, email, password, created) values (:name, :Nickname, :email, :password, now())");
    $res = $stmt->execute([
      ':name' => $values['name'],
      ':Nickname' => $values['Nickname'],
      ':email' => $values['email'],
      ':password' => password_hash($values['password'], PASSWORD_DEFAULT)
    ]);
    if ($res === false) {
      throw new \MyApp\Exception\DuplicateEmail();
    }
  }

  public function login($values) {
    $stmt = $this->db->prepare("select * from users where email = :email");
    $stmt->execute([
      ':email' => $values['email']
    ]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    $user = $stmt->fetch();

    if (empty($user)) {
      throw new \MyApp\Exception\UnmatchEmailOrPassword();
    }

    if (!password_verify($values['password'], $user->password)) {
      throw new \MyApp\Exception\UnmatchEmailOrPassword();
    }

    return $user;
  }

  public function findAll() {
    $stmt = $this->db->query("select * from users order by id");
    $stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
    return $stmt->fetchAll();
  }
}