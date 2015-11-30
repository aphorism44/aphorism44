<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        require('login_tools.php');
        load();
    }
    
    include('blogHeader.html');
    
    echo '<p>You can now edit the blog and comment entries.</p><br><br>';
    
    echo '<p>
            <a href="entry.php">Make Blog Entry</a><br>
            <a href="editEntries.php">Blog Entry Overview</a><br>
            <a href="editComments.php">Comment Overview</a><br>
            <a href="logoff.php">Logoff</a>
        </p>';
    
    include('blogFooter.html');    
?>