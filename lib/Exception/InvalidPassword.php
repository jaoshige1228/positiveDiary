<?php

namespace MyApp\Exception;

class InvalidPassword extends \Exception {
  protected $message = 'パスワードの形式が正しくありません';
}