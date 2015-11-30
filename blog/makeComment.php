<?php
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            require ('otherResources/main_connect_db.php');
            
            $entryId = $_POST['entryId'];
            $comment = substr(mysqli_real_escape_string($dbc, $_POST['commentText']), 0, 1500);
            $comment = strip_tags($comment);
            
            if ($comment != null && strlen($comment) > 0) {
                $qry = "
                    INSERT INTO Data_Comments(entry_id, comment_text, date_created) 
                    VALUES ($entryId, '$comment', CURDATE())
                    ";
            } 
            
            $r = mysqli_query($dbc, $qry);
            
            if (mysqli_error($dbc)) {
                echo $qry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                header('Location: blogEntry.php?entryId=' . $entryId);
            }
         
            mysqli_close($dbc);

        
    } //end post
    
    
         
     
    
?>