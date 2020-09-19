<?php require APPROOT . '/views/inc/header.php'; ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Email Verification</h2>
        <br>
        <div class="alert <?php echo (empty($data['verify_err'])) ? 'alert-success' : 'alert-danger'?>" role="alert">
        <?php echo (empty($data['verify_err'])) ? '<i class="fa fa-check"></i> Account Successfully Verified' : '<i class="fa fa-exclamation-triangle"></i> '.' '.$data['verify_err']; ?>
        </div>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>