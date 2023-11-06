<!DOCTYPE HTML>
<html lang="en">

<head>
    <title> Edit Story </title>
</head>

<body>
    <!-- Displays the original story in a input field for users to edit -->
    <?php
        require 'database.php';
        session_start();

        // Check if edit story button is pressed and the user is logged in
        if (isset($_POST['editStory']) && isset($_SESSION['username']) && isset($_POST['edit']))
        {
            $username = (string) $_SESSION['username'];
            $story = (string) $_POST['edit'];

            $stmt = $mysqli->prepare("SELECT title, body, link from stories where story_id = (select story_id from stories where title = ?)");
            $stmt->bind_param('s', $story);
            $stmt->execute();
            $stmt->bind_result($title, $body, $link);
            $stmt->fetch();
            $stmt->close();
        }
        else
        {
            header('Location: home.php');
        }
    ?>

    <!-- Edit story button -->
    <form action = 'edit_story.php' method = 'post'>
        Title <br> <textarea name = 'title' cols = '30', rows = '5'><?php echo $title; ?></textarea> <br>
        Story <br> <textarea name = 'body' cols = '50', rows = '10'><?php echo $body; ?></textarea> <br>
        Link (optional): <?php echo $link; ?> <br> <input type = "url" name="link"> <br>
        <input type = 'submit' name = 'editSubmit' value = 'Make changes'/>
        <input type = 'hidden' name = 'og_title' value = <?php echo $title; ?> />
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
    </form>

    <br>

    <!-- Reset story to original content without editing -->
    <form action = 'user_view.php'>
        <input type = 'submit' value = 'Discard changes and go back to user stories'/>
        <input type = "hidden" name = "token" value = "<?php echo $_SESSION['token'];?>" />
    </form>
</body>
</html>