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
      }
    }
  }

  public function loginCheck(){

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

      header('Location: ' . SITE_URL . '/top.php');
      exit;
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
      session_regenerate_id(true);
      $_SESSION['me'] = $user;

      // redirect to home
      header('Location: ' . SITE_URL);
      exit;
    }
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