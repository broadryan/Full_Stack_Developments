<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title> News Site </title>
        <link rel="stylesheet" href="homeFile.css">
    </head>
    <body>
        <h1>
            WatchU News: We Watch, U Choose 
        </h1>

        <br>
        
        <?php
            session_start();
            if (isset($_SESSION['username']))
            {
                // Welcome user
                printf("Welcome %s!", $_SESSION['username']);
            }
        ?>
        
        <?php
            $token = $_SESSION['token'];
            if (isset($_SESSION['username']))
            {
                // Show log out button
                echo "<form action = 'logout.php'>
                <input type = 'submit' value = 'Log Out'/>
                </form>";
                //Show User's uploaded stories
                echo "<form class action = 'user_view.php' method = 'post'>
                <input type = 'submit' value = 'View your stories'/>
                <input type = 'hidden' name = 'token' value = '$token' />
                </form>";
            }
            else
            {
                // Show login 
                echo " <form class action = 'login.php'>
                <input type = 'submit' value = 'Sign In'/>
                </form>";
            }

            //Show signup
            echo "<form class action = 'signup.php'>
            <input type = 'submit' value = 'Create Account'/>
            </form>";
        ?>

        <br>

        <!-- News stories shown below (maybe randomly) -->
        <br>
       
        <h2>
            What do you want to share today??
        
        <!-- Field for users to upload story including its title, body, and link -->
        <form action='storyupload.php' method = 'post'>  
            <b>Title</b><textarea minlength = '1' name='title' cols='60' rows='2'></textarea> <br>
            <b>Story</b><textarea minlength = '1' name='story' cols='60' rows='5'></textarea> <br>
            <b>Link (Optional)</b><input type = "url" name="link">
        <input type = 'submit' name = 'upload_story' value = 'upload'/></form>
        <input type = 'hidden' name = 'token' value = "<?php echo $_SESSION['token'];?>" />
        </h2>
        <br>
        
        <!-- Show all stories from highest story ID to lowest story ID -->
        <h3> <strong> Recent News </strong> </h3>
        <table>
            <tr>
                <th> Title </th>
                <th> Author </th>
                <th> Time Posted </th>
                <th> Likes </th>
            </tr>

            <?php
                require 'database.php';
                //Select username, title, publish date, story id, like count of the story
                $stmt = $mysqli->prepare("SELECT username,title,publish_date,story_id,like_count from stories");
                
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->execute();
                $stmt->bind_result($uname, $story_title, $date, $story_id, $likecount);

                while ($stmt->fetch())
                {
                    //Fill tables by username, date posted, number of likes, and a button for like for each story.
                    echo "<tr>
                        <td><a href='storyviewer.php?title=$story_title'>$story_title</a></td>\n\t\t
                        <td>$uname</td>\n\t\t
                        <td>$date</td>\n\t\t
                        <td>$likecount<form action = 'likeSubmit.php' method = 'post'>
                            <input type = 'submit' name = 'like' value = 'Like'/>
                            <input type = 'hidden' name = 'username' value = '$uname'/>
                            <input type = 'hidden' name = 'story_id' value = $story_id />
                            <input type = 'hidden' name = 'token' value = $token />
                            </form> </td>
                    </tr>";
                }

                $stmt->close();
            ?>
        </table>

    </body>
</html>