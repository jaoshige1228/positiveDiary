<?php

namespace MyApp\Exception;

class DuplicateEmail extends \Exception {
  protected $message = '使用されているメールアドレスです';
}