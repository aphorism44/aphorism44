<?php
    
    session_start();
    
    require('login_tools.php');
    
    if (!isset($_SESSION['user_id'])) {
        load();
    }
    
    include('blogHeader.html');
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            require ('blogResources/connect_db.php');
            
            $responseId = $_POST['responseId'];
            $commentId = $_POST['commentId'];
            $response = mysqli_real_escape_string($dbc, $_POST['responseText']);
            
            if (isset($_POST['deleteResponse'])) {
                $qry = "DELETE FROM Data_Responses WHERE comment_id = $commentId";
            } else {
                $qry = "
                    INSERT INTO Data_Responses(response_id, comment_id, response_text) 
                    VALUES ($responseId, $commentId,'$response')
                    ON DUPLICATE KEY UPDATE response_text='$response'
                    ";
            } 
            
            $r = mysqli_query($dbc, $qry);
            
            if (mysqli_error($dbc)) {
                echo $qry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                echo '<p>Response processed.</p><br>';
                echo $response . '<br><br>';
                echo '<p><a href="editComments.php">Return to Comments Page</a>';
            }
         
            mysqli_close($dbc);

        
    } //end post
    
    
         
   
         
    include('blogFooter.html');
     
    
?>