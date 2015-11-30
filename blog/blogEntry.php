<?php
    session_start();
    include('../include/config.php');
    include('../include/header.html');
    
    require ('otherResources/main_connect_db.php');
    
    //user is coming from blogList or makeComment page     
    $entryId = $_GET['entryId'];
    
    $qry = "SELECT entry_id, entry_title, entry_text, date_created FROM Data_Entries WHERE entry_id = $entryId";
    $res = mysqli_query($dbc, $qry) or die(mysqli_error($dbc));;
    
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    
    $entryText = $row['entry_text'];
    $pageTitle = $row['entry_title'];
    $dateCreated = $row['date_created'];
    
    if (mysqli_error($dbc)) {
                echo $qry;
                echo '<p>Error</p>' . mysqli_error($dbc);
            } else {
                echo '<title>Blog - ' . $pageTitle . '</title></head><body>';
                echo '</head><body><header><h2>' . $pageTitle . '</h1></header>';
                echo '<div id="main"><br><br>';
                echo $entryText;
            }
    
    
    echo '<br><br>';
    
    //the comments go here
    $commentQry = "SELECT comment_id, comment_text, date_created FROM Data_Comments WHERE entry_id = $entryId";
    $commentRes = mysqli_query($dbc, $commentQry) or die(mysqli_error($dbc));
    $numComments = mysqli_num_rows($commentRes);
    
    if ($numComments > 0) {
        echo '<table>';
                 
         while ($rowC = mysqli_fetch_array($commentRes, MYSQLI_ASSOC)) {
             
                 
            $comment = $rowC['comment_text'];
            $date = $rowC['date_created'];
                 
            echo '<tr><td id="commentTable">' . $comment . '<br><em>Posted: ' . $date . '</em></td></tr>';
            
             //show any responses made
            $commentId =  $rowC['comment_id'];
            $responseQry = "SELECT response_id, response_text FROM Data_Responses WHERE comment_id = $commentId";
            $responseRes = mysqli_query($dbc, $responseQry) or die(mysqli_error($dbc));
            if (mysqli_num_rows($responseRes) > 0) {
                $rowR = mysqli_fetch_array($responseRes, MYSQLI_ASSOC);
                $responseText = $rowR['response_text'];
                echo '<tr><td id="commentTable" style="padding-left:25px"><em><strong>44 Responds</em></strong>:<br>' . $responseText . '</td></tr>';
                }
         }
         echo '</table>';
    } else {
        echo 'No comments yet.';
    }
    
    //comment textbox
    echo '<br><br>';
    if ($numComments < 5) {
        echo '<form action="makeComment.php" method="post" accept-charset="utf-8"> 
             <input type="hidden" name="entryId" value="' . $entryId . '">
            <p>
                Comment: <textarea name="commentText" cols="50" rows = "5" maxlength="1500" wrap="soft"></textarea>
            </p>
            <p>
                <input type="submit" value="Submit Comment">
            </p>
        </form><br><br><br>';
    } else {
        echo 'Comments closed.';
    }
    
    mysqli_close($dbc);
    echo '<br><br>';
    include('../include/footer.html');    
?>