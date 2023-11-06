<!-- Upload story -->
<?php
    session_start();
    require 'database.php';
    //Check if upload story button has been pressed
    if(isset($_POST['upload_story']))
    {
        $story_content = (string) $_POST['story'];
        $title_content = (string) $_POST['title'];
        $link = (string) $_POST['link'];
        //Check if the story body or the title field is empty
        if (strlen(trim($story_content)) > 0 && strlen(trim($title_content)) > 0)
        {
            //insert new username, title, body, link into the stories table
            $stmt = $mysqli->prepare("insert into stories (username, title, body, link) values (?,?,?,?)");
            if(!$stmt){
                printf("%s\n", $mysqli->error);
                exit;
            }
    
            $stmt->bind_param('ssss', $_SESSION['username'], $title_content, $story_content, $link);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: home.php");
?>