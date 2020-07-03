<?php

namespace MyApp\Exception;

class EmptyPost extends \Exception {
  protected $message = '未入力の項目があります';
}