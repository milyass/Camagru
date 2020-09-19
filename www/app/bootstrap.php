<?php
  // Load Config
  require_once 'config/config.php';
  // load helper
  require_once 'helpers/url_helper.php';
  require_once 'helpers/session_helper.php';

  // Autoload Core Libraries
  // ex spl_autoload_register(function(Core)){
  // require_once 'libraries/core.php'
  //}
  
  spl_autoload_register(function($className){
  require_once 'libraries/' . $className . '.php';
  });
  
