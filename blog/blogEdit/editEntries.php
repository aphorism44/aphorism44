<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        require('login_tools.php');
        load();
    }
    
    include('blogHeader.html');
    
    require ('blogResources/connect_db.php');
    
    echo '<p>Below are all your blog entries.</p><br><br>';
    
    $qry = "SELECT entry_id, entry_title, entry_text, date_created, is_visible FROM Data_Entries ORDER BY date_created";
    $res = mysqli_query($dbc, $qry);
    
    if (mysqli_num_rows($res) > 0) {
        echo '<table border="1">
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Is Visible?</th>
                    <th>Edit</th>
                    <th>Preview</th>
                 </tr>';
                 
         while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
             
             if ($row['is_visible'] == 1) $vis = "Yes"; else $vis = "No";
             $entryId = $row['entry_id'];
             
             echo '<tr>
                    <td>' . $row['entry_title'] . '</td>
                    <td>' . $row['date_created'] . '</td>
                    <td>' . $vis . '</td>
                    <td><a href="entry.php?entryId=' . $entryId . '">Edit</a></td>
                    <td><a href="preview.php?entryId=' . $entryId . '" target="_blank">Preview</a></td>
                ';
       
         }
         echo '</table>';
    } else {
        echo 'No entries available.';
    }
    
    
    echo '<p>
            <a href="entry.php">Make Blog Entry</a><br>
            <a href="editEntries.php">Blog Entry Overview</a><br>
            <a href="editComments.php">Comment Overview</a><br>
            <a href="logoff.php">Logoff</a>
        </p>';
    
    mysqli_close($dbc);
    
    include('blogFooter.html');    
?>