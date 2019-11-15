<?php

//require('../vendor/autoload.php');

//$dbh = new PDO(getenv('DATABASE_URL'));

function get_header($title = 'Link Scrapbook') {
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <title><?php echo $title; ?></title>
      <meta name="viewport" content="width=device-width">
      <link rel="stylesheet" type="text/css" href="/stylesheets/bootstrap.min.css" />
      <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Playfair+Display:400,700&display=swap" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
      <link rel="stylesheet" type="text/css" href="/stylesheets/main.css" />
    </head>
    <body>
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="/">Link Scrapbook</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="/my_scrapbooks.php">My Scrapbooks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/about.php">About</a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="container">
  <?php
}

function get_footer() {
  ?>
  </div></body></html>
  <?php
}

function check_login() {
  if (isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] === md5('asdasd')) return;

  $warning = null;

  if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($_POST['email'] === 'demo@demo.fi' && $_POST['password'] === '123') {
      setcookie('logged_in', md5('asdasd'), time() + (86400 * 30), "/"); // 86400 = 1 day
      return;
    } else {
      $warning = 'Wrong email or password.';
    }
  }

  get_header();
  ?>
    <form class="form-signin" method="post">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <?php if($warning !== null) : ?><div class="alert alert-danger" role="alert"><?php echo $warning; ?></div><?php endif; ?>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
  <?php
  get_footer();
  die();
}