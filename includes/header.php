<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$lang = $_SESSION['lang'] ?? 'en';
$active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Member Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Gym Member Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link<?php if ($active == 'index.php') echo ' active'; ?>" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if ($active == 'members.php') echo ' active'; ?>" href="members.php">Members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if ($active == 'progress.php') echo ' active'; ?>" href="progress.php">Progress</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if ($active == 'id_card.php') echo ' active'; ?>" href="id_card.php">ID Card</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php if ($active == 'reminder.php') echo ' active'; ?>" href="reminder.php">Reminders</a>
        </li>
      </ul>
      <div class="d-flex gap-2">
        <form method="post" action="language.php" class="me-2">
          <input type="hidden" name="toggle_lang" value="1">
          <button type="submit" class="btn btn-outline-light btn-sm">
            <?php echo $lang === 'en' ? 'हिंदी' : 'English'; ?>
          </button>
        </form>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>
<div class="container">