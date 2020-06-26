<?php

namespace MyApp\Model;

class SerifModel extends \MyApp\Model {
  public function getSerifNoKey($genre){
    $sql = "select serif, face from serif where genre = :genre and key1 is NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':genre',$genre,\PDO::PARAM_STR);
    $stmt->execute();
    return  $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getSerifOneKey($genre, $key1){
    $sql = "select serif, face from serif where genre = :genre and key1 = :key1 and key2 is NULL and key3 is NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':genre',$genre,\PDO::PARAM_STR);
    $stmt->bindvalue(':key1',$key1,\PDO::PARAM_STR);
    $stmt->execute();
    return  $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getSerifTwoKey($genre, $key1, $key2){
    $sql = "select serif, face from serif where genre = :genre and key1 = :key1 and key2 = :key2 and key3 is NULL";
    $stmt = $this->db->prepare($sql);
    $stmt->bindvalue(':genre',$genre,\PDO::PARAM_STR);
    $stmt->bindvalue(':key1',$key1,\PDO::PARAM_STR);
    $stmt->bindvalue(':key2',$key2,\PDO::PARAM_STR);
    $stmt->execute();
    return  $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}