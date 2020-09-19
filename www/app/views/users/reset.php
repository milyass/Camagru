<?php require APPROOT . '/views/inc/header.php'; ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Reset Password</h2>
        <p>Please enter your email to request your password change link</p>
        <form action="<?php echo URLROOT; ?>/users/reset" method="post">
        <div class="form-group">
            <label for="email">Email: <sup>*</sup></label>
            <?php echo (!empty($data['reset_success'])) ? '<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> '.$data['reset_success'].'</div>' : ''?>
            <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
          </div>
          <div class="row">
            <div class="col">
              <input type="submit" value="Reset" class="btn btn-info btn-block">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>