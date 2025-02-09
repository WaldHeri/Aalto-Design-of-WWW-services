<?php
session_start();
require('../functions.php');
if (isset($_POST['submitCreate']) or isset($_POST['submitCopy'])) {
    if ($dbConnection === false) {
        header("Location: ../my_scrapbooks.php?create=error");
        exit();
    }
    if (empty($_POST['scrapbook_id'])) {
        header("Location: ../my_scrapbooks.php?=error");
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
            $link_id = create_link($dbConnection, $_POST);

            if ($link_id === null) {
                header("Location: ../my_scrapbooks.php?create=error");
                exit();
            }
        }

        $notes = $_POST['notes'];
        date_default_timezone_set('Europe/Helsinki');
        $updated = date('Y-m-d H:i:s');

        $query = "INSERT INTO public.\"Scrap\" (link_id, notes, updated) VALUES ($1, $2, $3) RETURNING id";
        $scrap_result = pg_prepare($dbConnection, "scrap", $query);
        $scrap_result = pg_execute($dbConnection, "scrap", array($link_id, $notes, $updated));

        if ($scrap_result === false) {
            header("Location: ../my_scrapbooks.php?create=error");
            exit();
        }

        $row = pg_fetch_row($scrap_result);
        $scrap_id = $row['0'];

        $query = "INSERT INTO public.\"HasScrap\" (scrapbook_id, scrap_id) VALUES ($1, $2)";
        $has_scrap_result = pg_prepare($dbConnection, "has_scrap", $query);
        $has_scrap_result = pg_execute($dbConnection, "has_scrap", array($scrapbook_id, $scrap_id));

        if ($has_scrap_result === false) {
            header("Location: ../my_scrapbooks.php?create=error");
            exit();
        }
        $keywords = $_POST['tags'];
        add_hashtags($dbConnection, $scrap_id, $keywords);
        header("Location: ../scrapbook.php?id=$scrapbook_id");
        exit();
    }
} else {
    header("Location: ../my_scrapbooks.php?create=error");
    exit();
}

function create_link($dbConnection, $post)
{

    $url = $post['url'];
    $tags = get_tags($url);
    $image_url = $tags['image'];
    $title = $tags['title'];
    $description = $tags['description'];

    $query = "INSERT INTO public.\"Link\" (url, image_url, title, description) VALUES ($1, $2, $3, $4) RETURNING id";
    $link_result = pg_prepare($dbConnection, "link", $query);
    $link_result = pg_execute($dbConnection, "link", array($url, $image_url, $title, $description));
    if ($link_result === false) {
        return null;
    }

    $row = pg_fetch_row($link_result);
    return $row['0'];
}


// https://stackoverflow.com/questions/3711357/getting-title-and-meta-tags-from-external-website
function get_tags($url, $specificTags=0)
{
    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . file_get_contents($url));
    $res['title'] = $doc->getElementsByTagName('title')->item(0)->nodeValue;

    foreach ($doc->getElementsByTagName('meta') as $m){
        $tag = $m->getAttribute('name') ?: $m->getAttribute('property');
        if(in_array($tag,['description','keywords']) || strpos($tag,'og:')===0) $res[str_replace('og:','',$tag)] = $m->getAttribute('content');
    }
    return  $res;
}


function add_hashtags($dbConnection, $scrap_id, $keywords){
    $arr = explode(' ', $keywords);
    pg_prepare($dbConnection, 'tag', 'SELECT id FROM "Tag" WHERE name = $1');
    pg_prepare($dbConnection, 'createtag', 'INSERT INTO "Tag" (name) VALUES ($1) RETURNING id');
    pg_prepare($dbConnection, 'hastag', 'INSERT INTO "HasTag" (tag_id, scrap_id) VALUES ($1, $2)');
    foreach($arr as $word){
        $result = pg_execute($dbConnection, 'tag', array($word));
        $rows = pg_num_rows($result);
        if($rows > 0){
            pg_execute($dbConnection, 'hastag', array( pg_fetch_row($result)['0'], $scrap_id ));
        } else {
            $create_result = pg_execute($dbConnection, 'createtag', array($word));
            $row = pg_fetch_row($create_result);
            pg_execute($dbConnection, 'hastag', array($row['0'], $scrap_id ));
        }
    }
    return;
}

