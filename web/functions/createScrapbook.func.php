<?php
session_start();
require('../functions.php');
if (isset($_POST['submit'])) {
    if ($dbConnection === false) {
        header("Location: ../my_scrapbooks.php?create=error");
        exit();
    }
    $user_id = '1'; //$_SESSION['user_id']; TODO remove comment
    if (empty($user_id)) {
        header("Location: ../my_scrapbooks.php?login=error");
        exit();
    } else {

        $title = pg_escape_literal($dbConnection, $_POST['title']);
        $description = pg_escape_literal($dbConnection, $_POST["description"]);

        if (isset($_POST['public']) and $_POST['public'] == 'true') {
            $public = 't';
        }else{
            $public = 'f';
        }

        $query = "INSERT INTO public.\"Scrapbook\" (user_id, public, title, description) VALUES ($1, $2, $3, $4)";
        $result = pg_prepare($dbConnection, "scrapbook", $query);
        $result = pg_execute($dbConnection, "scrapbook", array($user_id, $public, $title, $description));

        if ($result === false) {
            header("Location: ../my_scrapbooks.php?create=error");
            exit();
        } else {
            header("Location: ../my_scrapbooks.php?create=success");
            exit();
        }
    }
} else {
    header("Location: ../my_scrapbooks.php?create=error");
    exit();
}
