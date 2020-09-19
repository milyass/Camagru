<?php
  session_start();
  
  function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    else{
        return false;
    }
}
    function createUserSession($user = '',$dark){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['darkmode'] = $dark;
    redirect('pages/index');
}