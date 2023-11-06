<!DOCTYPE HTML>
<html lang="en">

<head>
    <title> Edit Comment </title>
</head>
    <body>
        <!-- Provides Input field for users to edit their own comment. -->
        <?php
            session_start();
            //Checks for cross-site request forgery.
            if (!hash_equals($_SESSION['token'],$_POST['token'])){
                
                die("Request forgery detected");
            }
            $comment_id = (int) $_POST['comm_id'];
            $comment = (string) $_POST['ed_comment'];
            $commenter = (string) $_POST['ed_user'];
            $title = (string) $_POST['ed_story_title'];
            $id = (int) $_POST['ed_story_id'];
            $token = $_SESSION['token'];

            //If edit comment button is pressed, updates content in the comments table.
            if (isset($_POST['editComm']) && isset($_SESSION['username']) && ($_POST['ed_user'] == $_SESSION['username']))
            {
                echo "<form action = 'comment_edit.php' method = 'post'>
                Comment <br> <textarea name = 'newcomment' cols = '30', rows = '5'>$comment</textarea> <br>
                <input type = 'submit' name = 'comm_edit' value = 'Make changes'/>
                <input type = 'hidden' name = 'id' value = $id/>
                <input type = 'hidden' name = 'title' value = '$title'/>
                <input type = 'hidden' name = 'name' value = '$commenter'/>
                <input type = 'hidden' name = 'content' value = '$comment'/>
                <input type = 'hidden' name = 'comm_id' value = $comment_id/>
                <input type = 'hidden' name = 'token' value = '$token'/>

                </form>";
            }
            else if (!isset($_POST['ed_story_title']))
            {
                // In case of invalid user attempting to edit someone else's comment, nothing happens
                header("Location: storyviewer.php?title=$title"); 
            }
            else
            {
                header("Location: home.php");
            }

        ?>
    </body>
</html>