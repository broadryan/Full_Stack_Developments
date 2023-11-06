<?php
    require 'database.php';
    session_start();
    //Check for cross-site request forgery
    if (!hash_equals($_SESSION['token'],$_POST['token'])){
        die("Request forgery detected");
    }
    //Check if the like button is pressed and username is set
    if (isset($_POST['like']) & isset($_SESSION['username']))
    {
        $story_id = (int) $_POST['story_id'];
        $username = (string) $_SESSION['username'];
        $title = (string) $_POST['title'];

        // Check if user has already clicked this before
        $stmt = $mysqli->prepare("SELECT count(*) from likes where story_id = ? and username = ?");

        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('is', $story_id, $username);
        $stmt->execute();
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();

        // If the like button has been pressed already, decrement the like count (dislike function)
        if ($cnt > 0)
        {
            $stmt_like = $mysqli->prepare("DELETE from likes where story_id = ? and username = ?");
            if(!$stmt_like){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
    
            $stmt_like->bind_param('is', $story_id, $username);
            $stmt_like->execute();
            $stmt_like->close();

            $stmt_dec = $mysqli->prepare("UPDATE stories set like_count = like_count - 1 where story_id = ?");
            if(!$stmt_dec){
                printf("%s\n", $mysqli->error);
                exit;
            }
            $stmt_dec->bind_param('i', $story_id);
            $stmt_dec->execute();
            $stmt_dec->close();
        }
        //If the like button has not been pressed, increment the like count (like function)
        else 
        {
            $stmt_like = $mysqli->prepare("INSERT INTO likes (story_id, username) values (?, ?)");

            if(!$stmt_like){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
    
            $stmt_like->bind_param('is', $story_id, $username);
            $stmt_like->execute();
            $stmt_like->close();

            $stmt_dec = $mysqli->prepare("UPDATE stories set like_count = like_count + 1 where story_id = ?");
            if(!$stmt_dec){
                printf("%s\n", $mysqli->error);
                exit;
            }
            $stmt_dec->bind_param('i', $story_id);
            $stmt_dec->execute();
            $stmt_dec->close();
        }
    }  
    
    if (isset($_POST['story_viewer']))
    {
        header("Location: storyviewer.php?title=$title");
    }
    else
    {
        header('Location: home.php');
    }
?>


