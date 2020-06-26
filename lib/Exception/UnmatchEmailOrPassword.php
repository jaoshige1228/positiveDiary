<?php

namespace MyApp\Exception;

class UnmatchEmailOrPassword extends \Exception {
  protected $message = 'メールアドレスとパスワードが一致しません';
}