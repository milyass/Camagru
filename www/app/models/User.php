<?php
class User {
  private $db;

  public function __construct(){
      $this->db = new Database;
  }

// Regsiter user
public function register($data){
      $hash = md5(rand(0,1000));
      $this->db->query('INSERT INTO users (name, email, password, token) VALUES(:name, :email, :password, :token)');
      // Bind values
      $this->db->bind(':name', strtolower($data['name']));
      $this->db->bind(':email', strtolower($data['email']));
      $this->db->bind(':password', $data['password']);
      $this->db->bind(':token', $hash);
       // sendig verif email 
        $to = $data['email'];
        $subject = 'Signup verification';
        $message = '
            <center>
            <h1 style="font-family:verdana;">Welcome To '.SITENAME.'</h1>
            <p style="font-family:verdana;">
            Hello Thanks for signing up <b> Mr '.strtolower($data['name']).'</b> Your account has been created<br>
            you can login with your credentials after you have activated your account clicking the url below:<br>
            <a target="_blank" style="color:#46C6C6" href="'.URLROOT.'/users/verify?hash='.$hash.'">Click here</a>
            </p>
          </center>';
        $headers = 'From:noreply@camagru.ma'."\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      // Execute
      if($this->db->execute() && mail($to,$subject,$message,$headers)){
        return true;
      } else {
        return false;
      }
}

// Login User
public function login($email, $password){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);
      $row = $this->db->single();
      $hashed_password = $row->password;
      if(password_verify($password, $hashed_password)){
        return $row;
      } else {
        return false;
      }
}

// Find user by email   
public function findUserByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      // Bind value
      $this->db->bind(':email', $email);
      $row = $this->db->single();
      // Check row
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
}

// find user by name
public function findUserByName($name){
  $this->db->query('SELECT * FROM users WHERE name = :name');
  // Bind value
  $this->db->bind(':name', $name);
  $row = $this->db->single();
  // Check row
  if($this->db->rowCount() > 0){
    return true;
  } else {
    return false;
  }
}
// verification email
public function verify($hash){ 
    /// verify in database token == hash
      $this->db->query('SELECT id, token FROM users WHERE token=:token AND active = 0');
      $this->db->bind(':token',$hash);
      if($row = $this->db->fetcher()){
        $this->db->query('UPDATE users SET active = 1, token = 0 WHERE id=:id');
        $this->db->bind(':id',$row['id']);
        if($this->db->execute()){
        return true;
        }
        else {
        return false;
        }
      }
      else
        return false;
}

// reset mail request
public function reset($email){
      $hash = md5(rand(0,1000));
      $this->db->query('UPDATE users SET reset=:hash WHERE email=:email');
      $this->db->bind(':email',$email);
      $this->db->bind(':hash',$hash);
      if($this->db->execute()){
        $to = $email;
        $subject = 'Reset password request';
        $message = '
            <center>
            <h1 style="font-family:verdana;">Reset Password</h1>
            <p style="font-family:verdana;">
            Hello You have Requested a password reset<br>
            if you ignore this message your password wont be changed<br>
            if you want to reset your password follow the link below:<br>
            <a target="_blank" style="color:#46C6C6" href="'.URLROOT.'/users/forgot/'.$hash.'">Click here</a>
            </p>
          </center>';
          $headers = 'From:noreply@camagru.ma'."\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(mail($to,$subject,$message,$headers)){
          return true;
        }
        else
        return false;
      }
      else
      return false;
}

// check if user active by email
public function isActive($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);
      
      if($row = $this->db->fetcher()){
        if($row['active'] == 1){
          return true;
        }else{
         return false;
        }
      }
      else
        return false;
}

// verify user by hash and see if hes active
public function verifyUserbyHash($hash){
  $this->db->query('SELECT * FROM users WHERE reset=:hash');
  $this->db->bind(':hash', $hash);

  if($row = $this->db->fetcher()){
    if($row['active'] == 1){
      return true;
    }else{
     return false;
    }
  }
  else
    return false;
}

// forgot password => changing password
public function forgot($data){
  //
  $this->db->query('UPDATE users SET password=:password WHERE reset=:token');
  $this->db->bind(':token', $data['hash']);
  $this->db->bind(':password', $data['password']);
      // Execute
  if($this->db->execute()){
    return true;
  } 
  else{
    return false;
  }
}

// edit user info
public function editinfo($data,$id){
  //UPDATE users SET name=:name, email=:email WHERE id=:id
  $this->db->query("UPDATE users SET name=:name, email=:email WHERE id=:id");
  $this->db->bind(':name', $data['name']);
  $this->db->bind(':email', $data['email']);
  $this->db->bind(':id', $id);
  if($this->db->execute()){
    return true;
  } 
  else{
    return false;
  }
}

public function chpwd($data,$id){
  $this->db->query('UPDATE users SET password=:newpwd  WHERE id=:id');
  $this->db->bind(':newpwd', $data['Newpassword']);
  $this->db->bind(':id', $id);
  $to = $data['email'];
  $subject = 'Password Change';
  $message = '
            <center>
            <h1 style="font-family:verdana;">Hello from '.SITENAME.'</h1>
            <p style="font-family:verdana;">
            Hello '.$data['name'].'</b> Your Password has been changed<br>
            you can login with your new password right now if it was not you please reset password using the link bellow<br>
            <a target="_blank" style="color:#46C6C6" href="'.URLROOT.'/users/reset">Click here</a>
            </p>
          </center>';
  $headers = 'From:noreply@camagru.ma'."\r\n";
  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
  if($this->db->execute() && mail($to,$subject,$message,$headers)){
    return true;
  } 
  else{
    return false;
  }


}

public function findUserByid($id){
  $this->db->query("SELECT name FROM users WHERE id = :id");
  $this->db->bind(':id', $id);
  $results = $this->db->resultSet();
  return $results;
}
// toggle notication

public function isNotified($uid){
$this->db->query("SELECT notification FROM users WHERE id=:id");
$this->db->bind(':id',$uid);
if($row = $this->db->fetcher()){
  if($row['notification'] == 1)
    return true;
  else
    return false;
}
else
  return false;
}

public function notify($uid,$value){
  $this->db->query("UPDATE users SET notification = :value WHERE id=:id");
  $this->db->bind(':id',$uid);
  $this->db->bind(':value',$value);
  if($this->db->execute())
    return true;
  else
    return false;
}

public function isDark($uid){
  $this->db->query("SELECT darkmode FROM users WHERE id=:id");
  $this->db->bind(':id',$uid);
  if($row = $this->db->fetcher()){
    if($row['darkmode'] == 1)
      return true;
    else
      return false;
  }
  else
    return false;
  }
  
  public function Dark($uid,$value){
    $this->db->query("UPDATE users SET darkmode = :value WHERE id=:id");
    $this->db->bind(':id',$uid);
    $this->db->bind(':value',$value);
    if($this->db->execute())
      return true;
    else
      return false;
  }


//END
}