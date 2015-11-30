<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        require('login_tools.php');
        load();
    }
    
    //if user came from editEntries or entry_action page
    if (isset($_GET['entryId'])) {
        
        require ('blogResources/connect_db.php');
        $eId = $_GET['entryId'];
        
        $editQry = "SELECT entry_title, entry_text FROM Data_Entries WHERE entry_id = '$eId'";
        $editQryR = mysqli_query($dbc, $editQry);
        if (mysqli_num_rows($editQryR) == 1) {
            $row = mysqli_fetch_array($editQryR, MYSQLI_ASSOC);
            $entryTitle = $row['entry_title'];
            $entryText = $row['entry_text'];
        }    
    } else {
        $eId = -1;
        $entryTitle = '';
        $entryText = '';
    }
    
    include('blogHeader.html');
    
    
    echo '<p>Enter your blog entry below. <strong>Be sure to check the box if you want it visible.</strong></p><br>';
    
    echo '<form action="entry_action.php" method="post" accept-charset="utf-8"> 
            <input type="hidden" name="entryId" value="' . $eId . '">
            <p>
                Blog Title: <input type="text" name="blogTitle" size = "50" value="' . $entryTitle . '">
            </p>
            <p>
                Blog Entry: <textarea name="blogEntry" cols="50" rows = "20" wrap="soft">' . $entryText . '</textarea>
            </p>
            <p>
                Visible? <input type="checkbox" name="isVisible">
            </p>
            <p>
                Delete? <input type="checkbox" name="delete">
            </p>
            <p>
                <input type="submit" value="Save Entry">
            </p>
        </form><br><br><br>';
    
    
    echo '<p>
            <a href="entry.php">Make Blog Entry</a><br>
            <a href="editEntries.php">Blog Entry Overview</a><br>
            <a href="editComments.php">Comment Overview</a><br>
            <a href="logoff.php">Logoff</a>
        </p>';
    
    include('blogFooter.html');    
?>