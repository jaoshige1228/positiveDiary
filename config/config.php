<?php

ini_set('display_errors', 1);

// ローカルサーバー用
// define('DSN', 'mysql:dbhost=localhost;dbname=diary;charset=utf8');
// define('DB_USERNAME', 'dbuser');
// define('DB_PASSWORD', 'kingdom');

// 公開サーバー用
$db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
$db['dbname'] = ltrim($db['path'], '/');
define('DSN', "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8");
define('DB_USERNAME', $db['user']);
define('DB_PASSWORD', $db['pass']);

// 画像関連の定数
define('MAX_FILE_SIZE', 1 * 1024 * 1024);
define('THUMBNAIL_WIDTH_AND_HEIGHT', 400);
define('IMAGES_DIR', __DIR__ . '/../public_html/images');
define('THUMBNAIL_DIR', __DIR__ . '/../public_html/thumbs');

define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

require_once(__DIR__ . '/../lib/functions.php');
require_once(__DIR__ . '/autoload.php');

session_start();
