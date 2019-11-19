<?php require('functions.php'); ?>
<?php

if (empty($_GET['id'])) {
  header('Location: /my_scrapbooks.php');
  die();
}
check_login();
$result = pg_prepare($dbConnection, "scrapbook", 'SELECT * FROM "Scrapbook" WHERE id = $1');
$result = pg_execute($dbConnection, "scrapbook", array($_GET['id']));

if (!$result) {
  header('Location: /my_scrapbooks.php');
  die();
}

$scrapbook = pg_fetch_array($result, NULL, PGSQL_ASSOC);

$result = pg_prepare($dbConnection, "scraps", 'SELECT "Scrap".*, "Link".* FROM "Scrap", "HasScrap", "Link" WHERE "Scrap".link_id = "Link".id AND "HasScrap".scrap_id = "Scrap".id AND "HasScrap".scrapbook_id = $1');
$result = pg_execute($dbConnection, "scraps", array($_GET['id']));

$scraps = pg_fetch_all($result);

?>

<?php get_header($scrapbook['title']); ?>

<h1><?php echo $scrapbook['title']; ?></h1>

<p>
  <a href="create_scrap.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary">Add new link</a>
</p>

<div class="row">
  <?php
  if (empty($scraps)) {
    echo '<h4>Your scrapbook is still empty. Add your first link from the button above!</h4>';
  } else {
    foreach ($scraps as $scrap) {
      echo '<div class="col-md-4">
      <div class="card mb-4 shadow-sm">';
      if (empty($scrap['image_url'])) {
        echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>' . $scrap['title'] . '</title><rect width="100%" height="100%" fill="#55595c"/><text x="5%" y="50%" fill="#eceeef" dy=".3em">' . $scrap['title'] . '</text></svg>';
      } else {
        echo '<img src="' . $scrap['image_url'] . '" width="100% height="225" >';
      }

      echo '<div class="card-body">
        <h3 class="mb-0">' . $scrap['title'] . '</h3>
        <p class="card-text mb-auto">' . substr($scrap['description'] , 0, min(strlen($scrap['description']), 150)) . '<br>. . .</p>
        <h4 class="mb-0">Notes:</h4>
          <p class="card-text">' . $scrap['notes'] . '</p>
          <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group">
              <a href="' . $scrap['url'] . '" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
              <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
            </div>
            <small class="text-muted">' . parse_timestamp($scrap['updated'],'M d Y H:i') . '</small>
          </div>
        </div>
      </div>
    </div>';
    }
  }
  ?>
</div>

<?php get_footer(); ?>