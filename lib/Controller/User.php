<?php

namespace MyApp\Controller;

class User extends \MyApp\Controller {
  public $name;
  public $icon;
  public $Nickname;
  public $firstPerson;
  public $NoteDays;
  public $KeepNoteDays;
  public $HighScoreNoteDays;

  public function __construct(){
    $user = new \MyApp\Model\User();
    $aboutMe = $user->GetMyData($_SESSION['me']->id);
    $this->name = $aboutMe['name'];
    $this->icon = $aboutMe['icon'];
    $this->Nickname = $aboutMe['Nickname'];
    $this->firstPerson = $aboutMe['firstPerson'];
    $this->NoteDays = $aboutMe['NoteDays'];
    $this->KeepNoteDays = $aboutMe['KeepNoteDays'];
    $this->HighScoreNoteDays = $aboutMe['HighScoreNoteDays'];
  }

  public function updateMyData($id){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if($_POST['key'] == 'myData'){
        $user = new \MyApp\Model\User();
        $user->updateMydata([
          'name' => $_POST['name'],
          'Nickname' => $_POST['Nickname'],
          'firstPerson' => $_POST['firstPerson'],
          'id' => $id
        ]);
    
        header('Location:'. SITE_URL . '/myPage.php');
      }
    }
  }

}