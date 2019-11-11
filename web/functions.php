<?php

require('../vendor/autoload.php');

//$dbh = new PDO(getenv('DATABASE_URL'));

function get_header($title = 'Link Scrapbook') {
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <title><?php echo $title; ?></title>
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
