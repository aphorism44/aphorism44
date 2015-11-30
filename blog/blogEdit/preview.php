<?php
    
    session_start();
    
    require('login_tools.php');
    
    if (!isset($_SESSION['user_id'])) {
        load();
    }
    
    include('../includes/mainHeader.html');
    //user is coming from editComments page
    require ('blogResources/connect_db.php');
    
    $entryId = $_GET['entryId'];
    
    $qry = "SELECT entry_text FROM Data_Entries WHERE entry_id = $entryId";
    $res = mysqli_query($dbc, $qry);
    
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    
    $entryText = $row['entry_text'];
    
    if (mysqli_error($dbc)) {
                echo $qry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                echo $entryText;
            }
         
    mysqli_close($dbc);
    
    
    include('blogHeader.html');
         
    echo '<p><a href="editEntries.php">Return to Edit Entries Page</a>';
         
    include('../includes/mainFooter.html');
     
    
?>