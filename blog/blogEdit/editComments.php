<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        require('login_tools.php');
        load();
    }
    
    include('blogHeader.html');
    
    require ('blogResources/connect_db.php');
    
    echo '<p>Below are all existing comments.</p><br><br>';
    
    $qry = "SELECT dc.comment_id, dc.comment_text, dc.date_created AS date_comment_created
            , de.entry_id, de.entry_title
            FROM Data_Comments dc
            JOIN Data_Entries de ON dc.entry_id = de.entry_id 
            ORDER BY de.entry_id";
    $res = mysqli_query($dbc, $qry);
    

    echo '<table border="1">
            <th>Blog Entry Title</th>
            <th>Comment</th>
             <th>Delete?</th>
             <th>Respond?</th>
           </tr>';
                 
    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
             
        $commentId = $row['comment_id'];
             
        echo '<tr>
               <td>' . $row['entry_title'] . '</td>
               <td>' . $row['comment_text'] . '</td>
               <td><a href="deleteComment.php?commentId=' . $commentId . '">Delete Comment</a></td>
               <td><a href="respondToComment.php?commentId=' . $commentId . '">Respond</a></td>
           </tr>';
       
    }
    echo '</table>';
    
    echo '<p>
            <a href="entry.php">Make Blog Entry</a><br>
            <a href="editEntries.php">Blog Entry Overview</a><br>
            <a href="editComments.php">Comment Overview</a><br>
            <a href="logoff.php">Logoff</a>
        </p>';
    
    mysqli_close($dbc);
    
    include('blogFooter.html');    
?>