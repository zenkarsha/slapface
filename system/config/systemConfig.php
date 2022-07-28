<?php

$config = array
(
  'site' => array(
    'parent' => '../../',
    'path' => 'http://'.$_SERVER['HTTP_HOST'].str_replace('index.php','',$_SERVER['PHP_SELF']),
    'url' => 'http://slapface.unlink.men/',
    'name'  => '打臉圖產生器',
    'title' => '打臉圖產生器',
    'description' => '我又跳出去啦！我又跳進來啦！打我呀，笨蛋！',
    'copyright' => 'just for fun',
    'shortcut-icon' => 'http://slapface.unlink.men/images/favicon.png',
    'apple-touch-icon' => ''
  ),
  'setting' => array(
    'enable-database' => false,
    'enable-navbar-search' => false,
    'enable-member-system' => false
  ),
  'member' => array(
    'default-page' => 'member'
  ),
  'database' => array(
    'host'  => '',
    'user'  => '',
    'pass'  => '',
    'db'  => ''
  ),
  'admin' => array(
    '000000000000000'
  ),
  'google' => array(
    'analytics-id'  => 'UA-00000000-0'
  ),
  'facebook' => array(
    'fanpage' => '',
    'app-id' => '',
    'app-secret' => '',
    'privacy-policy' => ''
  ),
  'og' => array(
    'title' => '打臉圖產生器',
    'type'  => 'website',
    'url' => 'http://slapface.unlink.men/',
    'image' => 'http://slapface.unlink.men/images/fb.png',
    'sitename'  => '打臉圖產生器',
    'description' => '我又跳出去啦！我又跳進來啦！打我呀，笨蛋！'
  )
);

?>
