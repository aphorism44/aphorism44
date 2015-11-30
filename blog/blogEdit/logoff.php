<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        require('login_tools.php');
        load();
    }
    
    include('blogHeader.html');
  
    $_SESSION = array(); //clears all existing session variables
    session_destroy(); //ends session
    
    echo '<p>You are now logged out.<br>';
    echo '<p><a href="login.php">Login again.</a><br>';
    echo '<p><a href="../../index.html">Return to main home page.</a>';
    
    include('blogFooter.html');    
?>
    
