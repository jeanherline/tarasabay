<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<ul class="sidebar navbar-nav">
  <li class="nav-item active">
    <a class="nav-link" href="dashboard.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-users"></i>
      <span>Profile</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <h6 class="dropdown-header">Manage</h6>
      <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/viewProfile.php">View Profile</a>
      <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/changePass.php">Change Password</a>
      <!-- <a class="dropdown-item" href="admin-manage-user.php">Manage</a> -->
    </div>
  </li>

  <?php
  if ($_SESSION['role'] == "driver") {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-id-card"></i>
        <span>Apply</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Registration</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/addCar.php">Add Car</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/registeredCars.php">Registered Cars</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/pendingCars.php">Pending Cars</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/declinedCars.php">Declined Cars</a>
      </div>
    </li>
  <?php
  }
  ?>

  <?php
  if ($_SESSION['role'] == "driver") {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-bus"></i>
        <span>Wallet</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">GCash</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-in.php">Cash-In</a>

        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-out.php">Cash-Out</a>
      </div>
    </li>
  <?php
  } else if ($_SESSION['role'] == "passenger") {
  ?>
    <li class="nav-item">
      <a class="nav-link" href="https://tarasabay.000webhostapp.com/cash-in.php">
        <i class="fas fa-fw fa-bus"></i>
        <span>Cash-In</span></a>
    </li>
  <?php
  }
  ?>

  <?php
  if ($_SESSION['role'] == "driver") {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-exchange-alt"></i> <!-- Change the icon class here -->
        <span>Transactions</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Wallet</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-in-history.php">Cash-In History</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-out-history.php">Cash-Out History</a>
      </div>
    </li>
  <?php
  } else if ($_SESSION['role'] == "passenger") {
  ?>
    <li class="nav-item">
      <a class="nav-link" href="https://tarasabay.000webhostapp.com/cash-in-history.php">
        <i class="fas fa-fw fa fa-history"></i>
        <span>Transactions</span></a>
    </li>
  <?php
  } else {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-car"></i> <!-- Change the icon class here -->
        <span>Car Registrants</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Table</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/pendingCars.php">Pending</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/registeredCars.php">Approved</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/declinedCars.php">Declined</a>
      </div>
    </li>
    <?php
    ?>

    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-book"></i> <!-- Change the icon class here -->
        <span>Manage Wallets</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Users</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-in-manage.php">Cash-In</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-out-manage.php">Cash-Out</a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-exchange-alt"></i> <!-- Change the icon class here -->
        <span>Transactions</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Table</h6>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-in-history.php">CI Table</a>
        <a class="dropdown-item" href="https://tarasabay.000webhostapp.com/cash-out-history.php">CO Table</a>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="https://tarasabay.000webhostapp.com/tickets.php">
        <i class="fa fa-ticket"></i>
        <span>Tickets</span></a>
    </li>

  <?php
  }
  ?>



  <!-- <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-book"></i>
      <span>Bookings</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <h6 class="dropdown-header">Bookings:</h6>
      <a class="dropdown-item" href="admin-add-booking.php">Add</a>
      <a class="dropdown-item" href="admin-view-booking.php">View</a>
      <a class="dropdown-item" href="admin-manage-booking.php">Manage</a>
    </div>
  </li>

  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-comments"></i>
      <span>Feedbacks</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <a class="dropdown-item" href="admin-view-feedback.php">View</a>
      <a class="dropdown-item" href="admin-publish-feedback.php">Manage</a>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="admin-pwd-resets.php">
      <i class="fas fa-fw fa-key"></i>
      <span>Password Resets</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="admin-view-syslogs.php">
      <i class="fas fa-fw fa fa-history"></i>
      <span>System Logs</span></a>
  </li> -->
</ul>