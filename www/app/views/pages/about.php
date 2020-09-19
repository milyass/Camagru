<?php require APPROOT . '/views/inc/header.php'; ?>
  <h1><?php echo $data['title']; ?></h1>
  <h2 style="color: green">Welcome Everything is Fine</h2>
  <p><?php echo $data['description']; ?></p>
  <p>Version: <strong><?php echo APPVERSION; ?></strong></p>
<?php require APPROOT . '/views/inc/footer.php'; ?>