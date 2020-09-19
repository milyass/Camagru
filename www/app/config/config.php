<?php
  // db params
  define('DB_HOST', 'mysql');
  define('DB_USER', 'root');
  define('DB_PASS', 'tiger');
  define('DB_NAME', 'Camagru');
  // App Root
  define('APPROOT', dirname(dirname(__FILE__)));
  // URL Root
  define('URLROOT', 'http://localhost');
  // Site Name
  define('SITENAME', 'Camagru');
  //app version
  define('APPVERSION','8.0.0');
  // image path
  define('IMGPATH', "/var/www/html/public/img/");
  define('STICKERPATH',"/var/www/html/public/img/Stickers/");
  // stickers aliases
  define('PEPE', stickerencode('pepe'));
  define('KID', stickerencode('kid'));
  define('PEPA', stickerencode('pepa'));
  define('ANGRY', stickerencode('angry'));
  define('THUMBS', stickerencode('thumbs'));
  define('MARVIN', stickerencode('marvin'));

  function stickerencode($name){
    $path= STICKERPATH.$name.'.png';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = base64_encode($data);
    return $base64;
  }