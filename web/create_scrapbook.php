<?php require('functions.php'); ?>
<?php check_login(); ?>

<?php get_header('My scrapbooks'); ?>

<h1>My scrapbooks</h1>
<form id="createForm" class="form-signin" method="post" action="functions/createScrapbook.func.php">
  <img class="mb-4" src="/docs/4.3/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Create new scrapbook</h1>
  <label for="inputTitle" class="sr-only">Scrapbook title</label>
  <input name="title" type="text" id="inputTitle" class="form-control" placeholder="Scrapbook title" required autofocus>
  <label for="inputDescription" class="sr-only">Description</label>
  <textarea name="description" class="form-control" rows="5" id="inputDescription" placeholder="Write your description here" form="createForm"></textarea>
  <div class="checkbox">
    <label><input name="public" type="checkbox" id="inputPublicity" value="true"> Public</label>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" >Create</button>
</form>
<?php get_footer(); ?>