<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title> Story </title>
        <link rel="stylesheet" href="homeFile.css">
    </head>
    <body>
        <form action = "home.php">
            <input type = "submit" value = "Return Home"/>
        </form>

        <?php
            function scrollStories()
            {
                require 'database.php';
                if (isset($_SESSION['username']))
                {
                    $stmt = $mysqli->prepare("select title from stories where username = ?");
                
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }

                    $stmt->bind_param('s', $_SESSION['username']);
                    $stmt->execute();
                    $stmt->bind_result($story_title);

                    while ($stmt->fetch())
                    {
                        echo "<option>".htmlentities($story_title)."</option>"; 
                    }
                }
            }
        ?>

        <form action = 'user_delete.php' method = 'post'>
            <select name = 'delete'>
                <?php
                    session_start();
                    scrollStories();
                ?>
            </select>
            <input type = 'submit' name = 'deleteStory' value = 'Delete Story'>
        </form>
        
        <form action = 'user_edit.php' method = 'post'>
            <select name = 'edit'>
                <?php
                    session_start();
                    scrollStories();
                ?>
            </select>
            <input type = 'submit' name = 'editStory' value = 'Edit Story'>
        </form>

        <table>
            <tr>
                <th> Title </th>
                <th> Story </th>
                <th> Time Posted </th>
            </tr>

            <?php
                session_start();
                if (isset($_POST['token'])){
                    if (!hash_equals($_SESSION['token'],$_POST['token'])){
                        die("Request forgery detected");
                    }
                }
                require 'database.php';
                if (isset($_SESSION['username']))
                {
                    $stmt = $mysqli->prepare("select title, body, publish_date from stories where username = ?");
                
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('s', $_SESSION['username']);
                    $stmt->execute();
                    $stmt->bind_result($story_title,$body,$date);

                    while ($stmt->fetch())
                    {
                        
                        echo "<tr>
                        <td>$story_title</td>\n\t\t<td>$body</td>\n\t\t<td>$date</td>
                        </tr>";
                    }
                }
                else
                {
                    header('Location: home.php');
                }
            ?>
        </table>
    </body>
</html>