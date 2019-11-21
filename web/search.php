<?php require('functions.php'); ?>
<?php

session_start();
$tag_name = $_GET['tag'];
$tag_result = pg_prepare($dbConnection, "tags", 'SELECT id FROM "Tag" WHERE name = $1');
$tag_result = pg_execute($dbConnection, "tags", array($tag_name));
$row = pg_fetch_row($tag_result);
$tag_id = $row['0']; 

$result = pg_prepare($dbConnection, "scraps", 'SELECT "Scrap".*, "Link".* FROM "Scrap", "HasTag", "Link", "Scrapbook", "HasScrap", "Tag" WHERE ("Scrapbook".public OR "Scrapbook".user_id = $1) AND "HasScrap".scrapbook_id = "Scrapbook".id AND "Scrap".id = "HasScrap".scrap_id AND "Scrap".link_id = "Link".id AND "HasTag".scrap_id = "Scrap".id AND "Tag".id = "HasTag".tag_id AND "Tag".name = $2');
$result = pg_execute($dbConnection, "scraps", array($_SESSION['user_id'], $tag_name));

$scraps = pg_fetch_all($result);

?>

<?php get_header("Results for tag: " . htmlspecialchars($tag_name)); ?>

<h1><?php echo "Results for tag: " . htmlspecialchars($tag_name); ?></h1>

<div class="row">
  <?php
  if (empty($scraps)) {
    echo '<div class="alert alert-primary" role="alert">
    No results for tag: '. htmlspecialchars($tag_name) . '</div>';
  } else {
    foreach ($scraps as $scrap) {
      echo '<div class="col-md-4">
      <div class="card mb-4 shadow-sm">';
      if (empty($scrap['image_url'])) {
        echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>' . $scrap['title'] . '</title><rect width="100%" height="100%" fill="#55595c"/><text x="5%" y="50%" fill="#eceeef" dy=".3em">' . $scrap['title'] . '</text></svg>';
      } else {
        echo '<img src="' . filter_var($scrap['image_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) . '" width="100% height="225" >';
      }

      echo '<div class="card-body">
        <h5 class="mb-0">' . htmlspecialchars($scrap['title']) . '</h5>
        <p class="card-text">' . htmlspecialchars($scrap['notes']) . '</p>
        <div class="d-flex justify-content-between align-items-center">
          <div class="btn-group">
            <a href="' . filter_var($scrap['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) . '" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
          </div>
          <small class="text-muted">' . parse_timestamp($scrap['updated'],'M d Y H:i') . '</small>
        </div>
      </div>
    </div></div>';
    }
  }
  ?>
</div>

<?php get_footer(); ?>