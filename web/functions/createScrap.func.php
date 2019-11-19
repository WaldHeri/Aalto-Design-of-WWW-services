<?php
session_start();
require('../functions.php');
if (isset($_POST['submitCreate']) or isset($_POST['submitCopy'])) {
    if ($dbConnection === false) {
        header("Location: ../my_scrapbooks.php?create=error1");
        exit();
    }
    if (empty($_POST['scrapbook_id'])) {
        header("Location: ../my_scrapbooks.php?=error11");
        exit();
    }

    $scrapbook_id = $_POST['scrapbook_id'];


    $user_id = $_SESSION['user_id'];
    if (empty($user_id)) {
        header("Location: ../my_scrapbooks.php?login=error");
        exit();
    } else {
        $link_id = '';
        if (isset($_POST['submitCreate'])) {


            $url = $_POST['url'];
            $notes = $_POST["notes"];

            $query = "INSERT INTO public.\"Link\" (url) VALUES ($1) RETURNING id";
            $link_result = pg_prepare($dbConnection, "link", $query);
            $link_result = pg_execute($dbConnection, "link", array($url));
            if ($link_result === false) {
                header("Location: ../my_scrapbooks.php?create=error3");
                exit();
            }

            $row = pg_fetch_row($link_result);
            $link_id = $row['0'];
        }


        date_default_timezone_set('Europe/Helsinki');
        $updated = date('Y-m-d H:m:s');

        $query = "INSERT INTO public.\"Scrap\" (link_id, notes, updated) VALUES ($1, $2, $3) RETURNING id";
        $scrap_result = pg_prepare($dbConnection, "scrap", $query);
        $scrap_result = pg_execute($dbConnection, "scrap", array($link_id, $notes, $updated));
        
        if ($scrap_result === false) {
            header("Location: ../my_scrapbooks.php?create=error4");
            exit();
        }

        $row = pg_fetch_row($scrap_result);
        $scrap_id = $row['0'];

        $query = "INSERT INTO public.\"HasScrap\" (scrapbook_id, scrap_id) VALUES ($1, $2)";
        $has_scrap_result = pg_prepare($dbConnection, "has_scrap", $query);
        $has_scrap_result = pg_execute($dbConnection, "has_scrap", array($scrapbook_id, $scrap_id));

        if ($has_scrap_result === false) {
            header("Location: ../my_scrapbooks.php?create=error6");
            exit();
        }

        header("Location: ../scrapbook.php?id=$scrapbook_id");
        exit();
    }
} else {
    header("Location: ../my_scrapbooks.php?create=error5");
    exit();
}
