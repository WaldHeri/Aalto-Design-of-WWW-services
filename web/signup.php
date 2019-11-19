<?php require('functions.php'); ?>
<?php
$warning = null;
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'username') {
        $warning = 'Username already in use';
    } else if ($_GET['error'] == 'email') {
        $warning = 'Email address already in use';
    }
}
?>



<?php get_header('My scrapbooks'); ?>



<form id="createForm" method="post" action="functions/createUser.func.php" class="col-md-8">
    <h1 class="h3 mb-3 font-weight-normal">Please enter your account info</h1>
    <?php if ($warning !== null) : ?><div class="alert alert-danger" role="alert"><?php echo $warning; ?></div><?php endif; ?>
    <label for="inputUsername" class="sr-only">Username</label>
    <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="pwd" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <label for="inputPassword2" class="sr-only">Confirm Password</label>
    <input name="pwd2" type="password" id="inputPassword2" class="form-control" placeholder="Confirm Password" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign up</button>
</form>
<script>
    var pwd = document.getElementById("inputPassword"),
        pwd2 = document.getElementById("inputPassword2");

    function validatePassword() {
        if (pwd.value != pwd2.value) {
            pwd2.setCustomValidity("Passwords Don't Match");
        } else {
            pwd2.setCustomValidity('');
        }
    }

    pwd.onchange = validatePassword;
    pwd2.onkeyup = validatePassword;
</script>


<?php get_footer(); ?>