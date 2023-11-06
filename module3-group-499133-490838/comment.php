<!-- Inserts new comments into the comments table. -->
<?php
    session_start();
    require 'database.php';
    $title=$_SESSION['curr_story'];

    //Checks if submit button is pressed and comment input field is not empty.
    if (isset($_POST['submit']) && strlen(trim($_POST['comment'])) > 0)
    {
        //Checks if the user is logged in.
        if (isset($_SESSION['username']))
        {
            $comment = (string) $_POST['comment'];
            //insert new data in comments table/
            $stmt = $mysqli->prepare("insert into comments(story_id, content, username) values (?, ?, ?)");
            if (!$stmt)
            {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('iss', $_SESSION['curr_storyid'], $comment, $_SESSION['username']);
            $stmt->execute();
            $stmt->close();

        }
        //Goes back to the user's story view page.
        header("Location: storyviewer.php?title=$title");
    } 
    else
    {
        header("Location: home.php");
    }
?>