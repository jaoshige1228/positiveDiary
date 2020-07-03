<?php

namespace MyApp\Exception;

class TooLongName extends \Exception {
  protected $message = 'ユーザー名は全角10文字以内で';
}