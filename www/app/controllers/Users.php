<?php

class Users extends Controller {
/// constructor get model
public function __construct(){
    $this->userModel = $this->model('User');
}

////////////Register
public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
        // Process form
  
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data =[
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter email';
        } else {
          // Check email
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
          elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 250){
            $data['email_err'] = 'Email invalid';
          }
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }else{
          if(!preg_match("/^[0-9]*[a-zA-Z]+[0-9]*/",$data['name']) || strlen($data['name']) >= 20 || strlen($data['name']) < 5){
            $data['name_err'] = 'invalid username';
          }
          elseif($this->userModel->findUserByName($data['name'])){
            $data['name_err'] = 'username is already taken';
          }
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6 || strlen($data['password']) >= 128 || !preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/",$data['password'])){
          $data['password_err'] = 'Password must be at least 6 characters and a maximum of 128 At least one : upper case and lower case letter, one digit, one special character';
        }
        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }
        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Validated
          
          // Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Register User
          if($this->userModel->register($data)){
            $data = ['success_message' => 'Good Your profile was created successfully follow the link in your inbox to complete registration'];
            $this->view('users/right',$data);
          } else {
            die('Something went wrong');
          }

        } else {
          // Load view with errors
          $this->view('users/register', $data);
        }

      } else {
        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/register', $data);
      }
}

/////////// Login
public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Pleae enter email';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        }

        // Check for user/email
        if($this->userModel->findUserByEmail($data['email']) && $this->userModel->isActive($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'User Not Found or Not Active';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);
          if($loggedInUser){
            // Create Session
            if($this->userModel->isDark($loggedInUser->id))
              $dark = '1';
            else
              $dark = '0';
            createUserSession($loggedInUser,$dark);
          } else {
            $data['password_err'] = 'Password incorrect';

            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
        }


      } else {
        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('users/login', $data);
      }
}

///////// logout method
public function logout(){
  unset($_SESSION['user_id']);
  unset($_SESSION['user_email']);
  unset($_SESSION['user_name']);
  session_destroy();
  redirect('users/login');
}
////////// Verify mail
public function verify(){
  if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['hash'])){

      $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
      $data = [
        'verify'=> trim($_GET['hash']),
        'verify_err' => ''
      ];
      if(empty($data['verify'])){
        redirect('pages/index');
      }

      if($this->userModel->verify($data['verify'])){
        $this->view('users/verify', $data);
      }
      else {
        $data['verify_err'] = 'Something Went Wrong';
        $this->view('users/verify', $data);
      }
  }
}

/////////// Reset password mail
public function reset(){
  if(isLoggedIn()){
    redirect('users/chpwd');
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && !isLoggedIn()){
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data =[
          'email' => trim($_POST['email']),
          'email_err' => '',
          'reset_success'=>'',
        ];
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter email';
        } else {
          // Check email
          if($this->userModel->findUserByEmail($data['email'])){
            if($this->userModel->isActive($data['email'])){
              if($this->userModel->reset($data['email'])){
                $data['reset_success'] = 'All Good! Please click on ther link that has just been sent to you to reset your password';
              }
              else{
                $data['email_err'] = 'Something went wrong';
              }
            }
            else{
              $data['email_err'] = 'Account Not Active';
            }
          }
          else{
            $data['email_err'] = 'Email Not found';
          }

        }
    $this->view('users/reset', $data);
  }
  else{
    $data =[
      'email' => '',
      'email_err' => '',
      'reset_success'=>'',
    ];
    $this->view('users/reset', $data);
  }
}

//// change forgotten password
public function forgot($hash = ''){
  //// gets user by hash
  if(empty($hash)){
    redirect('pages/index');
  }
  if($this->userModel->verifyUserbyHash($hash)){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
      $data['hash'] = $hash;
      $this->view('users/forgot', $data);
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password']) && isset($_POST['confirm_password'])){
      // init data
      $data =[
        'hash' => $hash,
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'password_err' => '',
        'confirm_password_err' => ''
      ];
      // password errors
      if(empty($data['password'])){
        $data['password_err'] = 'Please enter password';
      }
      elseif(strlen($data['password']) < 6 || strlen($data['password']) >= 128 || !preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/",$data['password'])){
        $data['password_err'] = 'Password must be at least 6 characters and a maximum of 128';
      }
      if(empty($data['confirm_password'])){
      $data['confirm_password_err'] = 'Please confirm password';
      }
      elseif($data['password'] != $data['confirm_password']){
        $data['confirm_password_err'] = 'Passwords do not match';
      }
      if(empty($data['password_err']) && empty($data['confirm_password_err'])){
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if($this->userModel->forgot($data)){
          redirect('users/login');
        }
        else {
          // Load invalid link 
          $this->view('users/wrong', $data);
        }
      }
    }
    $this->view('users/forgot', $data);

  }
  else
  $this->view('users/wrong');
}

///// sucess test
public function success(){
  $this->view('users/right');
}

//// edit account
public function edit(){
 if(isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name'])){
    $uid = $_SESSION['user_id'];
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
      if($this->userModel->isNotified($uid))
        $data['notification'] = 1;
      else 
        $data['notification'] = 0;

      if($this->userModel->isDark($uid))
        $data['darkmode'] = 1;
      else 
        $data['darkmode'] = 0;
      $this->view('users/edit',$data);
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_SESSION['user_email'])){
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // Init data
      $data =[
      'name' => trim($_POST['name']),
      'email' => trim($_POST['email']),
      'password' => trim($_POST['password']),
      'notification' => '',
      'darkmode' => '',
      'name_err' => '',
      'email_err' => '',
      'password_err' => '',
      ];

      if($this->userModel->isNotified($uid))
      $data['notification'] = 1;
      else 
      $data['notification'] = 0;

      if($this->userModel->isDark($uid))
        $data['darkmode'] = 1;
      else 
        $data['darkmode'] = 0;
    
      // Validate Email
      if($_SESSION['user_email'] != $data['email']){
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter email';
        } else {
          // Check email
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Email is already taken';
          }
          elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 250){
            $data['email_err'] = 'Email invalid';
          }
          
        }
      }
      // validate name 
      if($_SESSION['user_name'] != $data['name']){
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }else{
          if(!preg_match("/^[0-9]*[a-zA-Z]+[0-9]*/",$data['name']) || strlen($data['name']) >= 20 || strlen($data['name']) < 5){
            $data['name_err'] = 'invalid username';
          }
          elseif($this->userModel->findUserByName($data['name'])){
            $data['name_err'] = 'username is already taken';
          }
        }
      }
      $userpassverify = $this->userModel->login($_SESSION['user_email'], $data['password']);
      // verifying password
      if(empty($data['password'])){
        $data['password_err'] = 'Please enter password';
      } elseif(strlen($data['password']) < 6 || !$userpassverify || strlen($data['password']) >= 128){
        $data['password_err'] = 'Wrong password';
      }
      ///// edit model load
      if(empty($data['email_err']) && empty($data['password_err']) && empty($data['name_err'])){
        if($_SESSION['user_email'] == $data['email'] && $_SESSION['user_name'] == $data['name']){
          $data = ['success_message' => 'Good Nothing has changed'];
          $this->view('users/right',$data);
        }
        else{
          if($this->userModel->editinfo($data,$_SESSION['user_id'])){
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['user_name'] = $data['name'];
            $data = ['success_message' => 'Good Your profile informations has been updated'];
            $this->view('users/right',$data);
          }
          else{
            die('Something went wrong');
          }
        }
      }
      else{
        $this->view('users/edit',$data);
      }
    }
  }
  else
  redirect('/users/login');
}

// change password
public function chpwd(){
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name'])){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->view('users/chpwd');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Oldpassword']) && isset($_POST['Newpassword']) && isset($_POST['ConfirmNewpassword']) && isset($_SESSION['user_email'])){
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // Init data
      $data =[
      'name' => $_SESSION['user_name'],
      'email' => $_SESSION['user_email'],
      'Oldpassword' => trim($_POST['Oldpassword']),
      'Oldpassword_err' => '',
      'Newpassword' => trim($_POST['Newpassword']),
      'Newpassword_err' => '',
      'ConfirmNewpassword' => trim($_POST['ConfirmNewpassword']),
      'ConfirmNewpassword_err' => '',
      ];
      $oldpassverify = $this->userModel->login($_SESSION['user_email'],$data['Oldpassword']);
      // Validate Old Password
      if(empty($data['Oldpassword'])){
        $data['Oldpassword_err'] = 'Please enter password';
      } elseif(strlen($data['Oldpassword']) < 6 || strlen($data['Oldpassword']) >= 128 || !$oldpassverify){
        $data['Oldpassword_err'] = 'Wrong password';
      }
      // Validate New Password
      if(empty($data['Newpassword'])){
        $data['Newpassword_err'] = 'Please enter password';
      } elseif(strlen($data['Newpassword']) < 6 || strlen($data['Newpassword']) >= 128 || !preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-])/",$data['Newpassword'])){
        $data['Newpassword_err'] = 'Password must be at least 6 characters and a maximum of 128 At least one : upper case and lower case letter, one digit, one special character';
      }
      // Validate Confirm Password
      if(empty($data['ConfirmNewpassword'])){
        $data['ConfirmNewpassword_err'] = 'Please confirm password';
      } else {
        if($data['Newpassword'] != $data['ConfirmNewpassword']){
          $data['ConfirmNewpassword_err'] = 'Passwords do not match';
        }
      }
      if(empty($data['ConfirmNewpassword_err']) && empty($data['Newpassword_err']) && empty($data['Oldpassword_err'])){
        $data['Newpassword'] = password_hash($data['Newpassword'], PASSWORD_DEFAULT);
        if($this->userModel->chpwd($data,$_SESSION['user_id'])){
          $data['success_message'] = 'Good Your password has been updated';
          $this->view('users/right',$data);
        }
        else{
          die('Something went wrong');
        }
      }
      else{
        $this->view('users/chpwd',$data);
      }
    }
  }
  else
  redirect('/users/login');
}

/// notify toggle 
public function notify(){
  if(!isLoggedIn()){
    redirect('users/login');
  }
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->view('users/wrong');
      die();
    }
    else if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $uid = $_SESSION['user_id'];
      
      if($this->userModel->isNotified($uid))
        if($this->userModel->notify($uid,0))
            echo "notification set to 0"; 
          else
            echo "error";
      else
        if($this->userModel->notify($uid,1))
          echo "notification set to 1";
        else
          echo "error";
    }
}

public function dark(){
  if(!isLoggedIn()){
    redirect('users/login');
  }
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
      $this->view('users/wrong');
      die();
    }
    else if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $uid = $_SESSION['user_id'];
      if($this->userModel->isDark($uid))
        if($this->userModel->Dark($uid,0)){
          $_SESSION['darkmode'] = '0';
          echo "darkmode set to 0"; 
        }
          else
            echo "error";
      else
        if($this->userModel->Dark($uid,1)){
          $_SESSION['darkmode'] = '1';
          echo "darkmode set to 1";
        }
         
        else
          echo "error";
    }

}


////End
}
