<?php

namespace MyApp\Controller;

class Signup extends \MyApp\Controller {

  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if($_POST['key'] === 'signup'){
        $this->signup_postProcess();
      }else if($_POST['key'] === 'login'){
        $this->login_postProcess();
      }else if($_POST['key'] === 'easyLogin'){
        $this->easylogin_postProcess();
      }
    }
  }
  
  public function loginCheck(){
    // トップページではログインチェックを行わない
  }

  // かんたんログイン処理
  protected function easylogin_postProcess(){
    $this->validateToken($_POST['token']);

    // ランダムなメルアドとパスワードを生成
    $email = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15). '@guest.com';
    $password = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

    $userModel = new \MyApp\Model\User();
    $userModel->create([
      'name' => 'ゲスト',
      'Nickname' => 'ゲストさん',
      'email' => $email,
      'password' => $password
    ]);
    $user = $userModel->login([
      'email' => $email,
      'password' => $password
    ]);

    // データベースにサンプル用の日記を登録
    $this->_setSampleDiary($user->id);
    // サンプル用の日記数記録を登録
    $this->_setSampleScore($user->id);

    $this->_loginComprete($user);
  }

  private function _setSampleDiary($id){
    $diaryModel = new \MyApp\Model\DiaryModel();

    // 今日の日付を取得し、逆算して日記を作成する
    $Today = new \DateTime('today');
    $today = $Today->format('Y-m-d-D');
    $one = $Today->modify('-1 day')->format('Y-m-d-D');
    $two = $Today->modify('-1 day')->format('Y-m-d-D');
    $three = $Today->modify('-2 day')->format('Y-m-d-D');
    $four = $Today->modify('-2 day')->format('Y-m-d-D');

    $diaryModel->createSampleDiary($id, $one, $two, $three, $four);
  }
  private function _setSampleScore($id){
    $userModel = new \MyApp\Model\User();
    $userModel->setSampleScore($id);
  }

  protected function signup_postProcess() {
    // バリデーション
    try {
      $this->_signup_validate();
    } catch (\MyApp\Exception\InvalidEmail $e) {
      $this->setErrors('email', $e->getMessage());
    } catch (\MyApp\Exception\InvalidPassword $e) {
      $this->setErrors('password', $e->getMessage());
    } catch (\MyApp\Exception\TooLongName $e) {
      $this->setErrors('name', $e->getMessage());
    }

    $this->setValues('email', $_POST['email']);
    $this->setValues('name', $_POST['name']);

    if ($this->hasError()) {
      $this->errMode = 'signUpError';
      return;
    } else {
      try {
        // アカウント作成
        $userModel = new \MyApp\Model\User();
        $userModel->create([
          'name' => $_POST['name'],
          'Nickname' => $_POST['name'] . "さん",
          'email' => $_POST['email'],
          'password' => $_POST['password']
        ]);
      } catch (\MyApp\Exception\DuplicateEmail $e) {
        $this->setErrors('email', $e->getMessage());
        $this->errMode = 'signUpError';
        return;
      }

      // サインアップに成功したらそのままログイン処理を行う
      $userModel = new \MyApp\Model\User();
      $user = $userModel->login([
        'email' => $_POST['email'],
        'password' => $_POST['password']
      ]);
      $this->_loginComprete($user);
    }
  }

  protected function login_postProcess() {
    try {
      $this->_login_validate();
    } catch (\MyApp\Exception\EmptyPost $e) {
      $this->setErrors('login', $e->getMessage());
    }

    $this->setValues('login_email', $_POST['email']);

    if ($this->hasError()) {
      $this->errMode = 'loginError';
      return;
    } else {
      try {
        $userModel = new \MyApp\Model\User();
        $user = $userModel->login([
          'email' => $_POST['email'],
          'password' => $_POST['password']
        ]);
      } catch (\MyApp\Exception\UnmatchEmailOrPassword $e) {
        $this->setErrors('login', $e->getMessage());
        $this->errMode = 'loginError';
        return;
      }

      // login処理
      $this->_loginComprete($user);
    }
  }
  private function _loginComprete($user){
    session_regenerate_id(true);
      $_SESSION['me'] = $user;

      // redirect to home
      header('Location: ' . SITE_URL);
      exit;
  }

  private function _signup_validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "トークンが正しくありません";
      exit;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      throw new \MyApp\Exception\InvalidEmail();
    }
    if (!preg_match('/\A[a-zA-Z0-9]+\z/', $_POST['password'])) {
      throw new \MyApp\Exception\InvalidPassword('パスワードの形式が正しくありません');
    }
    if ($_POST['password'] !== $_POST['password_confirm']) {
      throw new \MyApp\Exception\InvalidPassword('確認パスワードが一致しません');
    }
    if (mb_strwidth($_POST['name']) > 20) {
      throw new \MyApp\Exception\TooLongName();
    }
    if (mb_strwidth($_POST['name']) == 0) {
      throw new \MyApp\Exception\TooLongName('ユーザー名を入力してください');
    }
  }

  private function _login_validate() {
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
      echo "トークンが正しくありません";
      exit;
    }
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
      echo "無効な投稿です";
      exit;
    }
    if ($_POST['email'] === '' || $_POST['password'] === '') {
      throw new \MyApp\Exception\EmptyPost();
    }
  }

}