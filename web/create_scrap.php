<?php require('functions.php'); ?>
<?php check_login();

if (empty($_GET['id'])) {
    header('Location: /my_scrapbooks.php');
    die();
  }

?>



<?php get_header('My scrapbooks'); ?>

<form id="createForm" method="post" action="functions/createScrap.func.php" class="col-md-8">
  <h1 class="h1 mb-3 font-weight-normal">Create new scrap</h1>
  <div class="form-group">
    <label for="inputURL">Link url</label>
    <input name="url" type="text" id="inputURL" class="form-control" placeholder="Link url" required autofocus>
  </div>
  <div class="form-group">
    <label for="inputNotes">Notes</label>
    <textarea name="notes" class="form-control" rows="5" id="inputNotes" placeholder="Your notes about the link" form="createForm"></textarea>
  </div>
  <input hidden="true" name="scrapbook_id" value="<?php echo $_GET['id']; ?>" form="createForm">
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submitCreate" >Create</button>
</form>
<?php get_footer(); ?>