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
    
    $modQry = "SELECT dr.response_id, dc.comment_id, dc.comment_text AS comment_text, dr.response_text 
        FROM Data_Comments dc
        LEFT JOIN Data_Responses dr ON dc.comment_id = dr.comment_id
        WHERE dc.comment_id = $commentId";
    $modQryR = mysqli_query($dbc, $modQry);
    $row = mysqli_fetch_array($modQryR, MYSQLI_ASSOC);
    
    echo 'Comment: ' . $row['comment_text'] . '<br><br>'; 
    
    if (empty($row['response_id'])) $responseId = -1; else $responseId = $row['response_id'];
    
    echo '<form action="respond_action.php" method="post" accept-charset="utf-8"> 
             <input type="hidden" name="responseId" value="' . $responseId . '">
            <input type="hidden" name="commentId" value="' . $row['comment_id'] . '">
            <p>
                Response: <textarea name="responseText" cols="50" rows = "20" wrap="soft">' . $row['response_text']  . '</textarea>
            </p>
            <p>
                Delete? <input type="checkbox" name="deleteResponse">
            </p>
            <p>
                <input type="submit" value="Save Response">
            </p>
        </form><br><br><br>';
    
    mysqli_close($dbc);
    
    
    include('blogHeader.html');
         
    echo '<p><a href="editComments.php">Return to Comments Page</a>';
         
    include('blogFooter.html');
     
    
?>