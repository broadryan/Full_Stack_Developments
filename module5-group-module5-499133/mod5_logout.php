<!-- Logout -->
<?php
    session_start() ;
    session_destroy();
    header('Location: mod5_home.php');
?>