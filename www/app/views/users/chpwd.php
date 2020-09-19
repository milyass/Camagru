<?php require APPROOT . '/views/inc/header.php'; ?>


<div class="row">
<div class="col-md-8 mx-auto">
<div class="card">
<div class="card-body mt-8">
<h2 class="card-title">Change Password</h2>
<hr>
<!-- card start -->
<div class="row">
<div class="col">
<form action="<?php echo URLROOT; ?>/users/chpwd" method="post">
<div class="form-group">
<label for="Oldpassword">Old Password: </label>
<input type="password" name="Oldpassword" class="form-control <?php echo (!empty($data['Oldpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php if(!empty($_SESSION['Oldpassword'])) echo $data['Oldpassword']; ?>">
<span class="invalid-feedback"><?php echo $data['Oldpassword_err']; ?></span>
</div>
<div class="form-group">
<label for="Newpassword">New Password: </label>
<input type="password" name="Newpassword" class="form-control <?php echo (!empty($data['Newpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php if(!empty($_SESSION['Newpassword'])) echo $data['Newpassword']; ?>">
<span class="invalid-feedback"><?php echo $data['Newpassword_err']; ?></span>
</div>
<div class="form-group">
<label for="ConfirmNewpassword">Retype New Password to Confirm: </label>
<input type="password" name="ConfirmNewpassword" class="form-control <?php echo (!empty($data['ConfirmNewpassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php if(!empty($_SESSION['ConfirmNewpassword'])) echo $data['ConfirmNewpassword']; ?>">
<span class="invalid-feedback"><?php echo $data['ConfirmNewpassword_err']; ?></span>
</div>
</div>
</div>
<div class="row">
<div class="col-4 text-center">
<a onclick="history.back()" class="btn btn-dark">Back</a>
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

<?php require APPROOT . '/views/inc/footer.php'; ?>