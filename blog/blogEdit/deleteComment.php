<?php
    
    session_start();
    
    require('login_tools.php');
    
    if (!isset($_SESSION['user_id'])) {
        load();
    }
    
    include('blogHeader.html');
    
    //user is coming from editComments page
    require ('blogResources/connect_db.php');
    
    $commentId = $_GET['commentId'];
    
    $delQry = "DELETE FROM Data_Comments WHERE comment_id = $commentId";
    $delQryR = mysqli_query($dbc, $delQry);
    
    if (mysqli_error($dbc)) {
                echo $delQry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                echo 'That comment is deleted.<br>';
            }
         
    mysqli_close($dbc);
    
    
    include('blogHeader.html');
         
    echo '<p><a href="editComments.php">Return to Comments Page</a>';
         
    include('blogFooter.html');
     
    
?>