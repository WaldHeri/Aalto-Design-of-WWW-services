<?php require('functions.php'); ?>
<?php check_login(); ?>

<?php get_header('My scrapbooks'); ?>

<h1>My scrapbooks</h1>

<p>
  <a href="create_scrapbook.php" class="btn btn-primary">Create new scrapbook</a>
</p>

<div class="row mb-2">
  <?php

    $result = pg_query($dbConnection, "SELECT * FROM Scrapbook WHERE user_id = 1");

    if (!$result) {
        echo "An error occurred.\n";
        exit;
    }

    foreach(pg_fetch_all($result) as $scrapbook) {
      echo '<div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary">World</strong>
            <h3 class="mb-0">' . $scrapbook['title'] . '</h3>
            <div class="mb-1 text-muted">Last edited: Nov 12</div>
            <p class="card-text mb-auto">' . $scrapbook['description'] . '</p>
            <a href="/scrapbook.php?id=' . $scrapbook['id'] . '" class="stretched-link">View scrapbook</a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
          </div>
        </div>
      </div>';
    }


  ?>
</div>

<?php get_footer(); ?>