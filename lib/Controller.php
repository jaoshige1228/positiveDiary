<?php

namespace MyApp;

class Controller {

  private $_errors;
  private $_values;
  public $errMode;

  public function __construct() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
    $this->_errors = new \stdClass();
    $this->_values = new \stdClass();
    $this->loginCheck();
  }

  protected function validateToken($token){
    if (!isset($token) || $token !== $_SESSION['token']) {
      echo "トークンが正しくありません";
      exit;
    }
  }

  public function loginCheck(){
    if (!$this->isLoggedIn()) {
      header('Location: ' . SITE_URL . '/top.php');
      exit;
    }
  }

  protected function setValues($key, $value) {
    $this->_values->$key = $value;
  }

  public function getValues() {
    return $this->_values;
  }

  protected function setErrors($key, $error) {
    $this->_errors->$key = $error;
  }

  public function getErrors($key) {
    return isset($this->_errors->$key) ?  $this->_errors->$key : '';
  }

  protected function hasError() {
    return !empty(get_object_vars($this->_errors));
  }

  protected function isLoggedIn() {
    // $_SESSION['me']
    return isset($_SESSION['me']) && !empty($_SESSION['me']);
  }

  public function me() {
    return $this->isLoggedIn() ? $_SESSION['me'] : null;
  }
}