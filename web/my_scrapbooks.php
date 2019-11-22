<?php require('functions.php'); ?>
<?php session_start(); ?>
<?php check_login(); ?>

<?php get_header('My scrapbooks'); ?>

<h1>My scrapbooks</h1>

<p>
  <a href="create_scrapbook.php" class="btn btn-primary">Create new scrapbook</a>
</p>

<div class="row mb-2">
  <?php
  $user_id = $_SESSION['user_id'];
  $result = pg_query($dbConnection, 'SELECT * FROM "Scrapbook" WHERE user_id = ' . $user_id);

  if (!$result) {
    echo "An error occurred.\n";
    exit;
  }
  if (pg_num_rows($result) > 0) {
    pg_prepare($dbConnection, "updated", 'SELECT "Scrap".updated FROM "Scrap", "HasScrap" WHERE "HasScrap".scrap_id = "Scrap".id AND "HasScrap".scrapbook_id = $1');
    foreach (pg_fetch_all($result) as $scrapbook) {
      echo '<div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <h3 class="mb-0">' . htmlspecialchars($scrapbook['title']) . '</h3>
            <div class="mb-1 text-muted">Last edited: ' . parse_timestamp(max(get_updated_time($scrapbook['id']), $scrapbook['created'] )) . '</div>
            <p class="card-text mb-auto">' . htmlspecialchars($scrapbook['description']) . '</p>
            <a href="/scrapbook.php?id=' . $scrapbook['id'] . '" class="stretched-link" style="margin-top: 1em;">View scrapbook</a>
          </div>
        </div>
      </div>';
    }
  }


  ?>
</div>

<?php get_footer(); ?>