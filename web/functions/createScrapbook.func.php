<?php
session_start();
require('../functions.php');
if (isset($_POST['submit'])) {
    include 'dbHost.func.php';
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

        if (empty($_POST['public'])) {
            $public = false;
        }else{
            $public = true;
        }

        $query = "INSERT INTO public.\"Scrapbook\" (user_id, public, title, description) VALUES ($1, $2, $3, $4)";
        echo $query;
        $result = pg_prepare($dbConnection, "stmt", $query);
        $result = pg_execute($dbConnection, "stmt", array($user_id, $public, $title, $description));

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
