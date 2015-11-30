<?php
    
    session_start();
       
    if (!isset($_SESSION['user_id'])) {
        load();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (empty($_POST['blogTitle'])) {
            echo '<p>Enter a title.</p>';
        }
        
         if (empty($_POST['blogEntry'])) {
            echo '<p>Enter an entry.</p>';
        }
         
        if (!empty($_POST['blogTitle']) && !empty($_POST['blogEntry'])) {
            require ('blogResources/connect_db.php');
            
            $entryId = $_POST['entryId'];
            $tit = $_POST['blogTitle'];
            $ent = $_POST['blogEntry'];
            if (isset($_POST['isVisible'])) $vis = 1; else $vis = 0;
            
            if (isset($_POST['delete'])) {
                $qry = "DELETE FROM Data_Entries WHERE entry_id = $entryId";
            } else {
                $qry = "
                    INSERT INTO Data_Entries(entry_id, entry_title, entry_text, date_created, is_visible) 
                    VALUES ($entryId,'$tit', '$ent', CURDATE(), $vis)
                    ON DUPLICATE KEY UPDATE entry_title='$tit', entry_text='$ent', is_visible=$vis
                    ";
            } 
            
            $r = mysqli_query($dbc, $qry);
            
            if (mysqli_error($dbc)) {
                echo $qry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                header('Location: editEntries.php');
            }
         
            mysqli_close($dbc);
        }
        
        
    }
     
    
?>