<?php

namespace MyApp\Controller;

class Login extends \MyApp\Controller {

  public function run() {
    if ($this->isLoggedIn()) {
      header('Location: ' . SITE_URL);
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->postProcess();
    }
  }

  protected function login_postProcess() {
    try {
      $this->_login_validate();
    } catch (\MyApp\Exception\EmptyPost $e) {
      $this->setErrors('login', $e->getMessage());
    }

    $this->setValues('email', $_POST['email']);

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