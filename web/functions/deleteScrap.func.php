<?php
require('../functions.php');
session_start();
check_login();



$scrapbook_id = $_POST['scrapbook_id'];
$scrap_id = $_POST['scrap_id'];
$user_id = $_SESSION['user_id'];
$result = pg_prepare($dbConnection, "checkScrap", 'SELECT "Scrap".* FROM "Scrap", "HasScrap", "Scrapbook", "User" WHERE  "Scrap".id = $1 AND "HasScrap".scrap_id = "Scrap".id AND "HasScrap".scrapbook_id = "Scrapbook".id AND "Scrapbook".user_id = $2 AND "Scrapbook".id = $3');
$result = pg_execute($dbConnection, "checkScrap", array($scrap_id, $user_id, $scrapbook_id));

if ($result === false) {
    header("Location: ../scrapbook.php?id=$scrapbook_id&delete=false");
    exit();
}
$rows = pg_num_rows($result);
if ($rows > 0) {

    $delete_result = pg_prepare($dbConnection, "deleteScrap", 'DELETE FROM "Scrap" WHERE "Scrap".id = $1');
    $delete_result = pg_execute($dbConnection, "deleteScrap", array($scrap_id));

    if($delete_result === false){
        header("Location: ../scrapbook.php?id=$scrapbook_id&delete=false");
        exit();
    }

    header("Location: ../scrapbook.php?id=$scrapbook_id&delete=true");
    exit();
} else {

    header("Location: ../scrapbook.php?id=$scrapbook_id&delete=false");
    exit();
}
