<?php
$currentUserId = $_GET['id'] ?? $_SESSION['user_id'] ?? null;
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Tolga's Fitness Tracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="view_profile.php">View Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="log_exercise.php">Log Exercise</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Log Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>