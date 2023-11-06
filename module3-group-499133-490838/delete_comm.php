<!-- Deletes comment from the database -->
<?php
    require 'database.php';
    session_start();
    //Checks for cross site request forgery.
    if (!hash_equals($_SESSION['token'],$_POST['token'])){
        die("Request forgery detected");
    }
    //Checks if delete button is pressed and the user deleting the comment is the owner of the comment.
    if (isset($_POST['delComm']) && isset($_SESSION['username']) && $_POST['user'] == $_SESSION['username'])
    {
        $comm_id = (int) $_POST['comm_id'];
        $comment = (string) $_POST['comment'];
        $commenter = (string) $_POST['user'];
        $title = (string) $_POST['story_title'];
        $id = (int) $_POST['story_id'];
        //Find comment by username, story id and comment id and delete the data from comments table.
        $stmt = $mysqli->prepare("delete from comments where username = ? and story_id = ? and comment_id = ?");

        if(!$stmt)
        {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('sis', $commenter, $id, $comm_id);
        $stmt->execute();
        $stmt->close();
        header("Location: storyviewer.php?title=$title");
    }
    else
    {
        header('Location: home.php');
    }                   
?>