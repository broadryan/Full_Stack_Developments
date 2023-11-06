<!-- Edits comment in the databse -->
<?php
    require 'database.php';
    session_start();
    //Checks for cross-site request forgery
    if (!hash_equals($_SESSION['token'],$_POST['token'])){
        die("Request forgery detected");
    }

    $new_comment = (string) $_POST['newcomment'];
    $comment_id = (int) $_POST['comm_id'];
    $comment = (string) $_POST['content'];
    $title = (string) $_POST['title'];
    $id = (int) $_POST['id'];

    //If edit comment button is pressed, updates content in the comments table.
    if (isset($_POST['comm_edit']))
    {
        //find comment by story id and comment id and update the content field in comments table.
        $stmt = $mysqli->prepare("update comments set content = ? where story_id = ? and comment_id= ?");
        if(!$stmt)
        {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('sii', $new_comment, $id, $comment_id);
        $stmt->execute();
        $stmt->close();
        header("Location: storyviewer.php?title=$title");
    }
    //Else, redirect to homepage.
    else
    {
        header("Location: home.php");
    }
?>