<?php require APPROOT . '/views/inc/header.php'; ?>
<h1>No Photos</h1>
<div class="container">
<div class="row">
    <div class="col">
      <div class="card card-body bg-info">
        <h1><i class="far fa-frown"></i>No Pictures YET</h1>
        <hr>
        <h5>You can <b>take</b> or <b>upload</b> Pictures by pressing this Link<i class="fas fa-arrow-right"></i><a href="<?php echo URLROOT; ?>/posts/create"><i class="fas fa-camera-retro"></i> Take a Picture</a><h5>
      </div>
    </div>
  </div>
  <?php require APPROOT . '/views/inc/footer.php'; ?>