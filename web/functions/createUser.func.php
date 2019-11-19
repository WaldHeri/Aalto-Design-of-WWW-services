<?php
session_start();
require('../functions.php');
if (isset($_POST['submit'])) {
    if ($dbConnection === false) {
        header("Location: ../index.php?signup=error1");
        exit();
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['pwd2'];

    if ($pwd != $pwd2) {
        header("Location: ../index.php?signup=error2");
        exit();
    } else {
        if (empty($username) || empty($email) || empty($pwd)) {
            header("Location: ../index.php?signup=error3");
            exit();
        } else {
            if (email_in_use($email) ) {
                header("Location: ../signup.php?error=email");
                exit();
            } else if (username_in_use($username)) {
                header("Location: ../signup.php?error=username");
                exit();
            } else {
                
                $pwdhash = password_hash($pwd, PASSWORD_DEFAULT);

                $query = "INSERT INTO public.\"User\" (username, email, pwdhash) VALUES ($1, $2, $3) RETURNING id";
                $user_result = pg_prepare($dbConnection, "user", $query);
                $user_result = pg_execute($dbConnection, "user", array($username, $email, $pwdhash));

                if ($user_result === false) {
                    header("Location: ../index.php?signup=error5");
                    exit();
                } else {

                    $row = pg_fetch_row($user_result);
                    $user_id = $row['0'];
                    $_SESSION['LAST_ACTIVITY'] = time();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['logged_in'] = md5($user_id . $username);
                    header("Location: ../my_scrapbooks.php");
                    exit();
                }
            }
        }
    }
} else {
    header("Location: ../my_scrapbooks.php?create=error5");
    exit();
}

function username_in_use($username)
{
    require('../functions/dbHost.func.php');
    $query = "SELECT * FROM public.\"User\" WHERE username=$1;";
    $username_result = pg_prepare($dbConnection, "username", $query);
    $username_result = pg_execute($dbConnection, "username", array($username));

    $rows = pg_num_rows($username_result);
    if ($rows > 0) {
        return true;
    } else {
        return false;
    }
}


function email_in_use($email)
{
    require('../functions/dbHost.func.php');
    $query = "SELECT * FROM public.\"User\" WHERE email=$1;";
    $email_result = pg_prepare($dbConnection, "email", $query);
    $email_result = pg_execute($dbConnection, "email", array($email));

    $rows = pg_num_rows($email_result);
    if ($rows > 0) {
        return true;
    } else {
        return false;
    }
}
