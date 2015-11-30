<?php
    session_start();
    include('../include/config.php');
    include('../include/header.html');
    
    require ('otherResources/main_connect_db.php');
    
    echo '<title>Aphorism 44 Blog Entries</title></head><body>';
    echo '</head><body><header><h1>Blog Entries</h1></header>';
         
    
    $qry = "SELECT entry_id, entry_title, entry_text, date_created, is_visible FROM Data_Entries WHERE is_visible = 1 ORDER BY date_created DESC";
    $res = mysqli_query($dbc, $qry) or die(mysqli_error($dbc));
    
    if (mysqli_num_rows($res) > 0) {
        echo '<table border="0">
                    <th>Title</th>
                    <th>Date Created</th>
                 </tr>';
                 
         while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
             
                 
            $entryId = $row['entry_id'];
                 
            echo '<tr>
               <td><a href="blogEntry.php?entryId=' . $entryId . '">' . $row['entry_title'] . '</a></td>
               <td>' . $row['date_created'] . '</td>
                ';
                         
       
         }
         echo '</table>';
    } else {
        echo 'Sorry - no entries available.';
    }
    
    echo '<br><br>';
    include('../include/footer.html');    
?>