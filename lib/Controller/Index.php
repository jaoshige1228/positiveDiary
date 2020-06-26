<?php

namespace MyApp\Controller;

class Index extends \MyApp\Controller {

  public function run() {
    if (!$this->isLoggedIn()) {
      header('Location: ' . SITE_URL . '/top.php');
      exit;
    }
  }

  // 一時的措置

}