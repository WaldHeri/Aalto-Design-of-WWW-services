<?php require('functions.php'); ?>
<?php session_start(); ?>
<?php check_login(); ?>

<?php get_header('My scrapbooks'); ?>

<form id="createForm" method="post" action="functions/createScrapbook.func.php" class="col-md-8">
  <h1 class="h1 mb-3 font-weight-normal">Create new scrapbook</h1>
  <div class="form-group">
    <label for="inputTitle">Scrapbook title</label>
    <input name="title" type="text" id="inputTitle" class="form-control" placeholder="Scrapbook title" required autofocus>
  </div>
  <div class="form-group">
    <label for="inputDescription">Description</label>
    <textarea name="description" class="form-control" rows="5" id="inputDescription" placeholder="Write your description here" form="createForm"></textarea>
  </div>
  <div class="form-group">
    <div class="checkbox">
      <label><input name="public" type="checkbox" id="inputPublicity" value="true"> Public</label>
    </div>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" >Create</button>
</form>
<?php get_footer(); ?>