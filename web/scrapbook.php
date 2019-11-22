<?php require('functions.php'); ?>
<?php

session_start();

if (empty($_GET['id'])) {
  header('Location: /my_scrapbooks.php');
  die();
}

$result = pg_prepare($dbConnection, "scrapbook", 'SELECT * FROM "Scrapbook" WHERE id = $1');
$result = pg_execute($dbConnection, "scrapbook", array($_GET['id']));

if (!$result) {
  header('Location: /my_scrapbooks.php');
  die();
}

$scrapbook = pg_fetch_array($result, NULL, PGSQL_ASSOC);

if ($scrapbook['public'] === 'f') {
  if (!empty($_SESSION['user_id']) && $scrapbook['user_id'] === $_SESSION['user_id']) {
    check_login();
  } else {
    header('Location: /');
    die();
  }
}

$is_own_scrapbook = false;

if (!empty($_SESSION['user_id']) && $scrapbook['user_id'] === $_SESSION['user_id']) {
  $is_own_scrapbook = true;
}

$result = pg_prepare($dbConnection, "scraps", 'SELECT "Scrap".*, "Link".url, "Link".image_url, "Link".description, "Link".title FROM "Scrap", "HasScrap", "Link" WHERE "Scrap".link_id = "Link".id AND "HasScrap".scrap_id = "Scrap".id AND "HasScrap".scrapbook_id = $1');
$result = pg_execute($dbConnection, "scraps", array($_GET['id']));

$scraps = pg_fetch_all($result);

?>

<?php get_header(htmlspecialchars($scrapbook['title'])); ?>

<h1><?php echo htmlspecialchars($scrapbook['title']); ?></h1>

<?php if ($is_own_scrapbook) { ?>
  <p><a href="create_scrap.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary">Add new link</a></p>

  <?php if ($scrapbook['public'] === 'f') { ?>
    <div class="alert alert-secondary" role="alert">
      <p><strong>This scrapbook is public.</strong> Share it to your friends with link below.</p>
      <input type="text" class="form-control" disabled value="https://linkscrapbook.herokuapp.com/scrapbook.php?id=<?php echo $_GET['id']; ?>">
    </div>
  <?php } ?>
<?php } ?>


<?php
if (empty($scraps) && $is_own_scrapbook) {

  echo '<div class="alert alert-primary" role="alert">
    Your scrapbook is still empty. Add your first link from the button above!</div>';
} else if (empty($scraps) && !$is_own_scrapbook) {
  echo '<div class="alert alert-primary" role="alert">
    This scrapbook is still empty.</div>';
} else if (empty($scraps) && $scrapbook['public'] !== 'f') {
  header('Location: /my_scrapbooks.php');
  die();
} else {
  echo '<div class="row">';
  foreach ($scraps as $scrap) {
    $result = pg_prepare($dbConnection, "tags" . $scrap['id'], 'SELECT "Tag".* FROM "Tag", "HasTag" WHERE "HasTag".tag_id = "Tag".id AND "HasTag".scrap_id = $1');

    $result = pg_execute($dbConnection, "tags" . $scrap['id'], array($scrap['id']));

    $tags = pg_fetch_all($result);

    $tag_links = '';

    if (!empty($tags)) {
      $tag_links = '<p>';

      foreach($tags as $tag) {
        $tag_links .= '<span class="badge badge-primary"><a href="/search.php?tag=' . htmlspecialchars($tag['name']) . '">' . $tag['name'] . '</a></span> ';
      }

      $tag_links .= '</p>';
    }

    echo '<div class="col-md-4">
      <div class="card mb-4 shadow-sm">';
    if (empty($scrap['image_url'])) {
      echo '<svg class="bd-placeholder-img card-img-top" width="100%" hleight="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>' . $scrap['title'] . '</title><rect width="100%" height="100%" fill="#55595c"/><text x="5%" y="50%" fill="#eceeef" dy=".3em">' . $scrap['title'] . '</text></svg>';
    } else {
      echo '<img src="' . filter_var($scrap['image_url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) . '" width="100% height="225" >';
    }

    echo '<div class="card-body">
        <h5 class="mb-0">' . htmlspecialchars($scrap['title']) . '</h5>
        ' . $tag_links . '
        <p class="card-text">' . htmlspecialchars($scrap['notes']) . '</p>
        <div class="d-flex justify-content-between align-items-center">
          <div class="btn-group">
            <a href="' . filter_var($scrap['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) . '" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>';
    if ($is_own_scrapbook) {
      echo '<form name="deleteForm'. $scrap['id'] .'" id="deleteForm'. $scrap['id'] .'" class="form-inline" method="post" action="functions/deleteScrap.func.php">
              &nbsp;<button type="submit" value="delete" class="btn btn-sm btn-outline-danger" form="deleteForm'. $scrap['id'] .'">Delete</button>
              <input hidden="true" name="scrap_id" value="'. $scrap['id'] .'" form="deleteForm'. $scrap['id'] .'">
              <input hidden="true" name="scrapbook_id" value="'. $scrapbook['id'] .'" form="deleteForm'. $scrap['id'] .'">
            </form>
            <script type="text/javascript">
            var form = document.getElementById("deleteForm'. $scrap['id'] .'");
            form.onsubmit = function () {
                return confirm("Are you sure that you wish delete this scrap?");
            }
            </script>';
    }
    echo '</div>
          <small class="text-muted">' . parse_timestamp($scrap['updated'], 'M d Y H:i') . '</small>
        </div>
      </div>
    </div></div>';
  }
}
?>
</div>

<?php get_footer(); ?>