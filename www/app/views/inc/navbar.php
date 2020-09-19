<!-- <nav class="navbar navbar-expand-lg  navbar-dark mb-3" id="nav"> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3" id="nav">
  <div class="container">
      <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
        <ul class="navbar-nav ml-auto">
          <?php if(isset($_SESSION['user_id'])) : ?>
            <li class="nav-item">
            <div class="dropdown">
              <a class="nav-link text-white" onclick="navOption()">
              Hello <?php echo $_SESSION['user_name'];?><i class="far fa-caret-square-down fa-fw"></i>
              </a>
              <div class="dropdown-menu bg-dark" id="navOption">
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/users/edit"><i class="fas fa-user-cog"></i> Edit Account</a>
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/users/chpwd"><i class="fas fa-user-lock"></i> Change Password</a>
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/posts/create"><i class="fas fa-camera-retro"></i> Take a Picture</a>
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/posts/mypics"><i class="far fa-images"></i> My Pictures</a>
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/pages/about"><i class="fas fa-info-circle"></i> About</a>
                <a class="dropdown-item text-white" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
              </div>
              </div>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
            </li>
          <?php endif; ?>
        </ul>
    </div>
  </nav>
  <div class="container">
  