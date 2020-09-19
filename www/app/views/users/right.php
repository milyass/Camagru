<?php require APPROOT . '/views/inc/header.php'; ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body green accent-1 mt-5 text-dark">
        <h1>Success</h1>
        <hr>
        <h5><?php if(!empty($data['success_message'])) echo $data['success_message']; ?></h5>
        <div class="row">
        <div class="col-8"></div>
        <div class="col-4 text-center">
        <a href="<?php echo URLROOT; ?>/posts" class="btn btn-success">Back</a>
        </div>
      </div>
    </div>
  </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>