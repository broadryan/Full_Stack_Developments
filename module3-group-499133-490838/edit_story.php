<?php
    require 'database.php';
    session_start();

    //Checks for cross-site request forgery
    if (!hash_equals($_SESSION['token'],$_POST['token'])){
        die("Request forgery detected");
    }

    //Checks if the edit story button is pressed and that the username is set.
    if (isset($_POST['editSubmit']) && isset($_SESSION['username']))
    {
        $title = (string) $_POST['title'];
        $body = (string) $_POST['body']; 
        $link = (string) $_POST['link'];
        $og_title = (string) $_POST['og_title'];

        //Find story by title and update title,body and link in the stories table.
        $stmt = $mysqli->prepare("update stories set title = ?, body = ?, link = ? where title = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ssss', $title, $body, $link, $og_title);
        $stmt->execute();
        $stmt->close();
        header('Location: user_view.php');
    }
    else
    {
        header('Location: home.php');
    }
?>