<?php require APPROOT . '/views/inc/header.php'; ?>


<div class="row">
<div class="col-md-8 mx-auto">
<div class="card testimonial-card">
<div class="card-body mt-8">
<h2 class="card-title">Edit Account info</h2>
<hr>
<!-- card start -->
<div class="row">
<div class="col">
<form action="<?php echo URLROOT; ?>/users/edit" method="post">
<div class="form-group">
<label for="name" >User Name</label>
<input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php if(!empty($_SESSION['user_name'])) echo $_SESSION['user_name']; ?>">
<span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
</div>
<div class="form-group">
<label for="email">Email</label>
<input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php if(!empty($_SESSION['user_email'])) echo $_SESSION['user_email']; ?>">
<span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
</div>
<div class="form-group">
<label for="password">Retype Password to confirm: </label>
<input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="">
<span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
</div>
<hr>
<div class="d-flex justify-content-center">
<div class="order-1 p-4 custom-control custom-switch">
  <input type="checkbox" class="custom-control-input" id="notify" <?php echo (!empty($data['notification'] == 1)) ?  'checked' : 'unchecked'; ?>>
  <label class="custom-control-label" for="notify">Notifications</label>
</div>
<div class="order-2 p-4 custom-control custom-switch">
  <input type="checkbox" class="custom-control-input" id="dark" <?php echo (!empty($data['darkmode'] == 1)) ?  'checked' : 'unchecked'; ?>>
  <label class="custom-control-label" for="dark">DarkMode</label>
</div>
</div>
<hr>
</div>
</div>
<div class="row">
<div class="col-4 text-center">
<a onclick="history.back()" class="btn btn-dark ">Back</a>
</div>
<div class="col-4"></div>
<div class="col-4 text-center">
<button class="btn deep-blue-gradient" value="" type="submit">Save</button>
</div>
</div>
</form>
<!-- card end-->
</div>
</div>
</div>
</div>
<script>
// notif switch
var toggle = document.querySelector('input[id="notify"]');
toggle.addEventListener('change',function (){
var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost/users/notify');
xhr.onload = function(){
        if(xhr.status == 200){
            if(xhr.responseText == "error"){
                alert("Something Went Wrong");
            }
        }
    }
xhr.send();
}
);

//darkmode switch
var darkmode = document.querySelector('input[id="dark"]');
darkmode.addEventListener('change',function (){
var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost/users/dark');
xhr.onload = function(){
        if(xhr.status == 200){
            if(xhr.responseText == "error"){
                alert("Something Went Wrong");
            }
            else
                location.reload();
        }
    }
xhr.send();
}
);





</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>