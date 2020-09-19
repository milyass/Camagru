<?php
  class Posts extends Controller {
    public function __construct(){
      $this->postModel = $this->model('Post');
    }

    public function mypics($page = 1){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      if(!is_numeric($page) || $page < 1 ){
        $this->view('users/wrong');
        die();
      }
      $page = intval($page);
      if(!isset($_SESSION['user_id'])){
        $this->view('users/wrong');
        die();
      }
      $id = $_SESSION['user_id'];
      $number_of_results = $this->postModel->getNumberByid($id);
      $results_per_page = 6;
      $number_of_pages = ceil($number_of_results/$results_per_page);
      if($number_of_pages == 0 && $number_of_results == 0){
        $this->view('posts/nopics');
          die();
      }
      if($page > $number_of_pages){
          $this->view('users/wrong');
          die();
      }
      else{
      $this_page_first_result = ($page-1)*$results_per_page;
      $posts = $this->postModel->getAllMyImagesPaged($this_page_first_result,$id);
      if(empty($posts)){
        $extra = ['number_of_pages' => 1];
        $data=['posts' => $posts];
        $this->view('posts/mypics',$data,$extra);
      }
      else{
        $extra = ['number_of_pages' => $number_of_pages];
        $data=['posts' => $posts];
        $this->view('posts/mypics', $data,$extra);
      }
      
        }
    }

    public function jsonreturn(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
          $this->view('users/wrong');
          die();
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])){
          $posts = $this->postModel->getImages($_SESSION['user_id']);
          echo $jsonformat=json_encode($posts);
        }
    }
   
    public function create(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->view('posts/create');
    }

    public function upload(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sticker'])){
        $data = [
          'file' => '',
          'file_err' => ''
        ];
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png');
        $sticker = array('KID','THUMBS','PEPE','PEPA','ANGRY','MARVIN');
        if(in_array($_POST['sticker'],$sticker)){
          if(in_array($fileActualExt,$allowed)){
            if($fileError === 0){
              if($fileSize < 100000000 && $fileSize > 0){
                $info = getimagesize($fileTmpName);
                if($info[0] < 500 || $info[1] < 500){
                  echo "FILE ERROR";
                  die();
                }
                $fileNameNew = rand(1,500).uniqid('', true).".".$fileActualExt;
                $fileDestination = IMGPATH.$fileNameNew;
                if($fileActualExt === "png" && $info['mime'] === 'image/png')
                  $dest = imagecreatefrompng($fileTmpName);
                else if(($fileActualExt == "jpg" || $fileActualExt == "jpeg") && ($info['mime'] == "image/jpeg"))
                  $dest = imagecreatefromjpeg($fileTmpName);
                  else{
                    echo "Image Not Valid";
                    die();
                  }
                $src = imagecreatefromstring(base64_decode(constant($_POST['sticker'])));
                imagecopy($dest, $src, 50, 50, 0, 0, 200, 200);
                imagejpeg($dest,$fileDestination);
                $type = pathinfo($fileDestination, PATHINFO_EXTENSION);
                $data = file_get_contents($fileDestination);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                echo $base64;
                imagedestroy($dest);
                imagedestroy($src);
                unlink($fileDestination);
              }
              else{
                echo "File Too Large or Empty";
                }
            }
            else {
              echo "FILE ERROR";
            }
          }
          else{
            echo "Invalid File";
          }
        }
        else{
          echo "Error";
        }
      }
    }

    public function snap(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sticker'])){
        $stickerArray =  array('KID','THUMBS','PEPE','PEPA','ANGRY','MARVIN');
          if(!in_array($_POST['sticker'], $stickerArray)){
            echo "Error";
            die();
          }
        $canvas64 = $_POST['imgurl'];
        $canvas64 = str_replace("data:image/png;base64,",'',$canvas64);
        $src = imagecreatefromstring(base64_decode(constant($_POST['sticker'])));
        $dest = imagecreatefromstring(base64_decode($canvas64));
        $fileNameNew = rand(1,500).uniqid('', true).".jpg";
        $fileDestination = IMGPATH.$fileNameNew;
        imagecopy($dest, $src, 50, 50, 0, 0, 200, 200);
        imagejpeg($dest,$fileDestination);
        $size = filesize($fileDestination);
        if($size < 16000){
        unlink($fileDestination);
        imagedestroy($result);
        echo "Error";
        die();
        }
        $type = pathinfo($fileDestination, PATHINFO_EXTENSION);
        $data = file_get_contents($fileDestination);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        echo $base64;
        imagedestroy($dest);
        imagedestroy($src);
        unlink($fileDestination);
      }
    }

    public function saveit(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['takenpic']) && isset($_SESSION['user_id'])){
        $userid = $_SESSION['user_id'];
        $img64 = str_replace("data:image/png;base64,",'',$_POST['takenpic']);
        $fileNameNew = rand(1,500).uniqid('', true).".jpg";
        $fileDestination = IMGPATH.$fileNameNew;
        if(file_exists($fileDestination)){
          $fileNameNew = rand(1,500).uniqid('', true).".jpg";
          $fileDestination = IMGPATH.$fileNameNew;
        }
        $result = imagecreatefromstring(base64_decode($img64));
        imagejpeg($result,$fileDestination);
        $size = filesize($fileDestination);
        if($size < 7000){
        unlink($fileDestination);
        imagedestroy($result);
        echo "Error";
        die();
        }
        $this->postModel->savePic($fileNameNew,$userid);
        imagedestroy($result);
      }
    }
    
    public function deleteit(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      if(isset($_POST['pictureid']) && isset($_SESSION['user_id'])){
        $PictureId = $_POST['pictureid']; 
        if($owner = $this->postModel->findPicOwner($PictureId)){
          $fileDestination = IMGPATH.$owner['imageid'];
          if($_SESSION['user_id'] === $owner['userid']){
              if($this->postModel->deletePicture($PictureId) && file_exists($fileDestination)){
                unlink($fileDestination);
              }
              else{
                echo "ERROR";
              }
          }
        }
        else{
          echo "Error";
        }
      }
      else{
        echo "Something Went Wrong";
      }
    }

    public function liked(){
      if(isset($_POST['PictureId']) && isLoggedIn() && isset($_SESSION['user_id'])){
        if($this->postModel->doesitExist($_POST['PictureId']) === false){
          echo "error";
          die();
        }
        if($this->postModel->alreadyliked($_POST['PictureId'],$_SESSION['user_id'])){
          if($this->postModel->unliked($_POST['PictureId'],$_SESSION['user_id'])){
            echo "unLiked";
          }
          else
            echo "not unliked";
        }
        else{
          if($this->postModel->liked($_POST['PictureId'],$_SESSION['user_id']))
            echo "Liked";
          else
            echo "Err not liked";
        } 
      }
      else
        echo "pls login";
        die();
    }
    
    public function comment(){
      if($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn() && isset($_POST['commentID']) && isset($_POST['comment']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name']) && isset($_SESSION['user_id'])){
        $comment = htmlspecialchars($_POST['comment'],ENT_NOQUOTES);
        $cid = $_POST['commentID'];
        $uid = $_SESSION['user_id'];
        $data = [
        'notify' => 0,
        'email' => $_SESSION['user_email'],
        'name' => $_SESSION['user_name']
      ];
        if($this->postModel->isNotifiedPost($uid))
          $data['notify'] = 1;
        
        if($this->postModel->doesitExist($cid) === false){
          echo "error";
          die();
        }
        if($this->postModel->comment($comment,$cid,$uid)){
          echo "done";
          if($data['notify'] == 1){
            $to = $data['email'];
            $subject = SITENAME.' Notification';
            $message = '
                <center>
                <h1 style="font-family:verdana;">'.SITENAME.' Notification</h1>
                <p style="font-family:verdana;">
                Hello<b> Mr '.strtolower($data['name']).'</b> One of Your Post(s) has been Commented<br>
                You can Deactivate This message by switching notifications off in your account settings<br>
                </p>
              </center>';
              $headers = 'From:noreply@camagru.ma'."\r\n";
              $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to,$subject,$message,$headers);
          }
        }
        else
          echo "error";
        }
        else{
          echo "pls login";
          die();
        }
    }

    public function getcomment(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
          $this->view('users/wrong');
          die();
        }
        else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commentID'])){
          $comments = $this->postModel->getComments($_POST['commentID']);
          echo $jsonformat=json_encode($comments);
        }
    }

}