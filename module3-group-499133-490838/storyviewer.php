<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title> Story </title>
        <link rel="stylesheet" href="homeFile.css">
    </head>
    <body>
        <!-- Lets user view the stories and allows users to like and comment on the story-->
        <form action = "home.php">
            <input type = "submit" value = "Return Home"> </input>
            <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
        </form>

        <?php
            session_start();
            if (!isset($_SESSION['username']))
            {
                header('Location: home.php');
                exit;
            }

            $token = $_SESSION['token'];
            $_SESSION['curr_storyid'] = "";    
            $_SESSION['curr_story'] = "";    
            require 'database.php';
            $title = (string) $_GET['title'];
            // Find data by title and select title,username,body,publish_date,story_id,link,like_count from stories table
            $stmt = $mysqli->prepare("select title, username, body, publish_date, story_id, link, like_count from stories where title = ?");
                        
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }

            $stmt->bind_param('s', $title);
            $stmt->execute();
            $stmt->bind_result($story_title, $user, $content, $date, $id, $link, $like_count);
            $stmt->fetch();
            $_SESSION['curr_storyid'] = $id;
            $_SESSION['curr_story'] = $story_title;
            //Prints out the story title, content, author, date published of the viewing story.
            printf("<h1>WatchU News: We Watch, U Choose</h1><h4>%s</h4><p> Created by: <i>%s </i>at <i>%s</i></p><p>%s</p><p><a href = %s> %s</a></p><p>%d like(s)</p>", $story_title, $user, $date, $content,$link, $link, $like_count);
        
            $user = (string) $_SESSION['username'];
            // button for liking the story
            if (isset($_SESSION['username']))
            {
                echo "<form action = 'likeSubmit.php' method = 'post'>
                <input type = 'submit' name = 'like' value = 'Like'/>
                <input type = 'hidden' name = 'username' value = '$user' />
                <input type = 'hidden' name = 'story_id' value = $id />
                <input type = 'hidden' name = 'token' value = $token />
                <input type = 'hidden' name = 'story_viewer' value = 1/>
                <input type = 'hidden' name = 'title' value = '$title' />
                </form>";
            }
        ?>
        
        <!-- See delete, comment, or edit option if signed in and if they made said article -->
        <form action = "comment.php" method="post">
            Add Comment <br> <textarea cols = '50' rows = '5' name = "comment"></textarea> <br>
            <input type = "submit" name = "submit" value = "Post Comment"> </input>
            <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
        </form>

        <!-- See Comments  -->
        <table class = "comment">
            <tr>
                <th> Comment Section (Must be signed in to comment) </th>
            </tr>

            <?php
                require 'database.php';
                $stmt = $mysqli->prepare("select username, content, comment_id from comments where story_id = $id");
                
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $stmt->execute();
                $stmt->bind_result($commenter, $comment, $comment_id);

                // Buttons for deleting and editing comments
                while ($stmt->fetch())
                {
                    echo "<tr>
                    <td> <strong> $comment </strong> <br> posted by $commenter 

                    <form action = 'delete_comm.php' method = 'post'>
                        <input type = 'submit' name = 'delComm' value = 'Delete Comment'/>
                        <input type = 'hidden' name = 'comment' value = '$comment'/>
                        <input type = 'hidden' name = 'user' value = '$commenter'/>
                        <input type = 'hidden' name = 'story_title' value = '$title'/>
                        <input type = 'hidden' name = 'story_id' value = $id/>
                        <input type = 'hidden' name = 'comm_id' value = $comment_id/>
                        <input type = 'hidden' name = 'token' value = '$token'/>
                    </form>

                    <form action = 'edit_comm.php' method = 'post'>
                        <input type = 'submit' name = 'editComm' value = 'Edit Comment'/>
                        <input type = 'hidden' name = 'ed_comment' value = '$comment'/>
                        <input type = 'hidden' name = 'ed_user' value = '$commenter'/>
                        <input type = 'hidden' name = 'ed_story_title' value = '$title'/>
                        <input type = 'hidden' name = 'ed_story_id' value = $id/>
                        <input type = 'hidden' name = 'comm_id' value = $comment_id/>
                        <input type = 'hidden' name = 'token' value = '$token'/>
                    </form>
                    </td>
                    </tr>";
                }
            ?>
        </table>
    </body>
</html>