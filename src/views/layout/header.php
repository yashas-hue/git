<?php
$theme = $_COOKIE['theme'] ?? 'dark';
$isDark = $theme !== 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $isDark ? 'dark' : 'light' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? $config['app_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <script defer src="/assets/js/app.js"></script>
</head>
<body class="kp-body">
<header class="kp-header navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/">Karmapreneur</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topnav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/courses">Courses</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Consultancy</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Training</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
      </ul>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm" id="theme-toggle" type="button">Toggle Theme</button>
        <?php if (!empty($user)): ?>
          <a class="btn btn-primary btn-sm" href="/dashboard">Dashboard</a>
          <form method="post" action="/logout" class="d-inline">
            <?= App\security\Csrf::field() ?>
            <button class="btn btn-outline-danger btn-sm" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a class="btn btn-primary btn-sm" href="/signup">Sign Up</a>
          <a class="btn btn-outline-secondary btn-sm" href="/login">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<div class="kp-layout container-fluid">
  <div class="row">
    <aside class="col-12 col-md-3 col-lg-2 kp-sidebar">
      <nav class="nav flex-column">
        <a class="nav-link" href="/dashboard">Home</a>
        <a class="nav-link" href="/courses">Courses</a>
        <a class="nav-link" href="#">Consultancy</a>
        <a class="nav-link" href="#">Training</a>
        <a class="nav-link" href="#">Products</a>
        <a class="nav-link" href="#">Services</a>
        <a class="nav-link" href="#">Franchise</a>
        <a class="nav-link" href="/history">History</a>
        <a class="nav-link" href="/account">Account</a>
        <a class="nav-link" href="#">Contact</a>
      </nav>
    </aside>
    <main class="col-12 col-md-9 col-lg-10 kp-main">
