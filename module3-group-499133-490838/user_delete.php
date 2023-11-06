<?php
    require 'database.php';
    session_start();

    // Only works if you press delete button and are logged in
    if (isset($_POST['deleteStory']) && isset($_SESSION['username']))
    {
        $username = (string) $_SESSION['username'];
        $story = (string) $_POST['delete'];

        // Check # of comments from selected story and grab its id
        $stmt = $mysqli->prepare("SELECT count(*), story_id from comments where story_id = (select story_id from stories where title = ?)"); 
        $stmt->bind_param('s', $story);
        $stmt->execute();
        $stmt->bind_result($cnt, $id);
        $stmt->fetch();
        $stmt->close();

        // Delete comments if any exist
        if ($cnt > 0)
        {
            $stmt_del = $mysqli->prepare("delete from comments where story_id = ?");

            if(!$stmt_del)
            {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
            }

            $stmt_del->bind_param('i', $id);
            $stmt_del->execute();
            $stmt_del->close();
        }

        // Delete story after comments (if they exist) are gone
        $stmt_stor = $mysqli->prepare("delete from stories where title = ?");
                
        if(!$stmt_stor)
        {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }                

        $stmt_stor->bind_param('s', $story);
        $stmt_stor->execute();
        header('Location: user_view.php'); // return back to user view once task is done
    }
    else
    {
        header('Location: home.php');
    }
?>