<?php
session_start();
require('../functions.php');
if (isset($_POST['submitCreate']) or isset($_POST['submitCopy'])) {
    if ($dbConnection === false) {
        header("Location: ../my_scrapbooks.php?create=error1");
        exit();
    }
    if(empty($_POST['scrapbook_id'])){
        header("Location: ../my_scrapbooks.php?=error11");
        exit(); 
    }

    $scrapbook_id = $_POST['scrapbook_id'];


    $user_id = '1'; //$_SESSION['user_id']; TODO remove comment
    if (empty($user_id)) {
        header("Location: ../my_scrapbooks.php?login=error");
        exit();
    } else {
        $link_id = '';
        if (isset($_POST['submitCreate'])) {


            $url = pg_escape_literal($dbConnection, $_POST['url']);
            $notes = pg_escape_literal($dbConnection, $_POST["notes"]);

            $query = "INSERT INTO public.\"Link\" (url) VALUES ($1) RETURNING id";
            $result = pg_prepare($dbConnection, "link", $query);
            $result = pg_execute($dbConnection, "link", array($url));
            if ($result === false) {
                header("Location: ../my_scrapbooks.php?create=error3");
                exit();
            }

            $row = pg_fetch_row($result);
            $link_id = $row['0'];
        }


        date_default_timezone_set('Europe/Helsinki');
        $updated = date('Y-m-d H:m:s');

        $query = "INSERT INTO public.\"Scrap\" (link_id, notes, updated) VALUES ($1, $2, $3) RETURNING id";
        $result = pg_prepare($dbConnection, "scrap", $query);
        $result = pg_execute($dbConnection, "scrap", array($link_id, $notes, $updated));



        if ($result === false) {
            header("Location: ../my_scrapbooks.php?create=error4");
            exit();
        } else {
            header("Location: ../scrapbook.php?id=$scrapbook_id");
            exit();
        }
    }
} else {
    header("Location: ../my_scrapbooks.php?create=error5");
    exit();
}


