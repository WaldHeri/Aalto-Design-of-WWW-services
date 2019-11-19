<?php

require('functions/dbHost.func.php');

function get_header($title = 'Link Scrapbook')
{

  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="/stylesheets/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Playfair+Display:400,700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
      <div class="collapse navbar-collapse ">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Log out</a>
          </li>
        </ul>
    </nav>
    </div>
    <div class="container">
    <?php
    }

    function get_footer()
    {
      ?>
    </div>
  </body>

  </html>
<?php
}

function check_login()
{
  session_start();
  expire_session();
  require('functions/dbHost.func.php');
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === md5($_SESSION['user_id'] . $_SESSION['username'])) return;

  $warning = null;

  if (isset($_POST['username']) && isset($_POST['password'])) {

    $query = 'SELECT * FROM "User" WHERE username=$1';
    $login_result = pg_prepare($dbConnection, "login", $query);
    $login_result = pg_execute($dbConnection, "login", array($_POST['username']));

    if ($login_result === false) {
      $warning = 'Error.';
    } else {


      if (pg_num_rows($login_result) === 0) {
        $warning = 'Wrong username or password.';
      } else {
        $user_data = pg_fetch_array($login_result, 0, PGSQL_ASSOC);
        $pwdhash = $user_data['pwdhash'];
        if (password_verify($_POST['password'], $pwdhash)) {
          $_SESSION['LAST_ACTIVITY'] = time();
          $_SESSION['user_id'] = $user_data['id'];
          $_SESSION['username'] = $user_data['username'];
          $_SESSION['email'] = $user_data['email'];
          $_SESSION['logged_in'] = md5($_SESSION['user_id'] . $_SESSION['username']);



          return;
        } else {

          $warning =  'Wrong username or password.';
        }
      }
    }
  }





  get_header();
  ?>
  <form class="form-signin" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <?php if ($warning !== null) : ?><div class="alert alert-danger" role="alert"><?php echo $warning; ?></div><?php endif; ?>
    <label for="inputUsername" class="sr-only">Username</label>
    <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <h6 class="h4 mt-3 font-weight-normal">or</h6>
    <p><a href="signup.php" class="btn btn-primary btn-lg btn-block">Sign up</a></p>
  </form>
<?php
  get_footer();
  die();
}





function expire_session()
{
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 30 * 60)) {
    session_unset();
    session_destroy();
  }
  $_SESSION['LAST_ACTIVITY'] = time();
}

function parse_timestamp($timestamp, $format = 'M d Y')
{
  return date($format, strtotime($timestamp));
}

function get_updated_time($scrapbook_id)
{
  require('functions/dbHost.func.php');

  $time_result = pg_execute($dbConnection, "updated", array($scrapbook_id));
  $array = pg_fetch_all_columns($time_result, 0);
  if (!empty($array)) {
    return max($array);
  } else {
    return null;
  }
}
