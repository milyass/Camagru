<?php require APPROOT . '/views/inc/header.php'; ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Reset Your Password</h2>
        <p>Please fill out this form to reset your password</p>
        <form action="<?php echo URLROOT; ?>/users/forgot/<?echo $data['hash'];?>" method="post">
          <div class="form-group">
            <label for="password">New Password: <sup>*</sup></label>
            <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo (!empty($data['password'])) ? $data['password'] : '' ; ?>">
            <span class="invalid-feedback"><?php echo (!empty($data['password_err'])) ? $data['password_err'] : '' ; ?></span>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm New Password: <sup>*</sup></label>
            <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo (!empty($data['confirm_password'])) ? $data['confirm_password'] : '' ; ?>">
            <span class="invalid-feedback"><?php echo (!empty($data['confirm_password_err'])) ? $data['confirm_password_err'] : '' ; ?></span>
          </div>
          <div class="row">
            <div class="col">
              <input type="submit" value="Reset" class="btn btn-success btn-block">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>