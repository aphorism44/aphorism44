<?php //process fractal page
require ( 'connect_db.php' ) ;
require('../include/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php');
require('../include/chromePHP.php');
include 'phpFunctions.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
  }

//ChromePhp::log('trying to grab session data...');
$userId = $_SESSION['user_id'];
$entryId = $_SESSION['entry_id'];

//ChromePhp::log('$userId: ' + $userId);
//ChromePhp::log('$entryId: ' + $entryId);

if ($_POST) {
    
    
    //change entryId
    if ($_POST['action'] == 'setEntryId') {
        $_SESSION['entry_id'] = $_POST['entryId'];
    }
    
    //save entries
    if ($_POST['action'] == 'entryPost') {
        //sanitize strings
        $entryTitle = sanitizeString($dbc, $_POST["entry_title"]);
        $pureConfig = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($pureConfig);
        $entryText = $purifier->purify($_POST["entry_text"]);
        $entryText = mysqli_real_escape_string($dbc, $entryText);
        $entryDate = $_POST["entry_date"];
        
        //need the below code to ensire insertId comes out right
        if ( $entryId > 0 ) {
            $q = "UPDATE Fractal_Entries SET entry_title = '$entryTitle', entry_text = '$entryText', entry_date = '$entryDate', last_modified=NOW() WHERE entry_id = '$entryId'";
            $r = mysqli_query ( $dbc, $q ) ;
        } else {
            $q = "INSERT INTO Fractal_Entries(`entry_title`, `entry_text`, `entry_date`, `user_id`, `last_modified`)
                   VALUES('$entryTitle', '$entryText', '$entryDate', '$userId', NOW())";
             $r = mysqli_query ( $dbc, $q ) ;
             $last_id = mysqli_insert_id($dbc);
             $_SESSION['entry_id'] = $last_id;
             $entryId = $last_id;
        }
        
       //ChromePhp::log($entryId); 
       //ChromePhp::log($q); 
        
       echo $_SESSION['entry_id'];
        //ChromePhp::log($q);
        //ChromePhp::log($_SESSION['entry_id']);
        
    }
    
    //create new entry button
    if ($_POST['action'] == 'newEntry') {
        $_SESSION['entry_id'] = -1;
    }
    
    //switch entries button
    if ($_POST['action'] == 'switchEntry') {
        $_SESSION['entry_id'] = $_POST["switchEntryId"];
    }
    
    //delete entry
    if ($_POST['action'] == 'deleteEntry') {
        $deleteEntryId = $_POST["entryId"];
        if ($deleteEntryId==  $_SESSION['entry_id']) {
            $_SESSION['entry_id'] = -1;
        }
        
        $q = "DELETE FROM Fractal_Entries WHERE user_id = '$userId' AND entry_id = '$deleteEntryId'";
        $r = mysqli_query ( $dbc, $q ) ;
        echo json_encode($_POST);
    }
     
    //add new keyword
    if ($_POST['action'] == 'addKeyword') {
        //sanitize strings
        $keyword = sanitizeString($dbc, $_POST["newKeyword"]);
        
        $q = "INSERT INTO Fractal_Keywords(keyword_name, user_id, last_modified)
            VALUES('$keyword','$userId',NOW())";
        $r = mysqli_query ( $dbc, $q ) ;
        
        echo json_encode(mysqli_insert_id($dbc));
        
    }

    //update or add keyword data
    if ($_POST['action'] == 'updateKeywordData') {
        //sanitize strings
        $keyData = sanitizeString($dbc, $_POST["keywordData"]);
        $keyId = $_POST["keywordId"];
        $keyConnected = $_POST["keyConnected"];
        //ChromePhp::log($keyData);
       // ChromePhp::log($keyId);
       // ChromePhp::log($keyConnected);
        if ($keyConnected == '0') {
             $q = "INSERT INTO Fractal_Entry_Keywords(entry_id, keyword_id, link_desc, last_modified)
                VALUES('$entryId', '$keyId', '$keyData', NOW())";
        }  else {
             $q = "UPDATE Fractal_Entry_Keywords SET link_desc = '$keyData', last_modified = NOW() WHERE entry_id = '$entryId' AND keyword_id = '$keyId'";
        }
        
        $r = mysqli_query ( $dbc, $q ) ;
        echo json_encode($_POST);
    }

    //remove keyword data from entry
    if ($_POST['action'] == 'removeKeywordData') {
        $keyId = $_POST["keywordId"];
        $q = "DELETE FROM Fractal_Entry_Keywords WHERE entry_id = '$entryId' AND keyword_id = '$keyId'";
        $r = mysqli_query ( $dbc, $q ) ;
        echo json_encode($_POST);
    }
    
    //remove unused keyword from system
    if ($_POST['action'] == 'deleteUnusedKeyword') {
        $keyId = $_POST["keywordId"];
        $q = "DELETE FROM Fractal_Keywords WHERE user_id = '$userId' AND keyword_id = '$keyId'";
        $r = mysqli_query ( $dbc, $q ) ;
        echo json_encode($_POST);
    }
    
}

if ($_GET) {
    
    //get entry data
    if ($_GET['action'] == 'getEntryData') {
            
        if ($entryId > 0) {
            $q = "SELECT entry_id, entry_title, entry_text, entry_date FROM Fractal_Entries WHERE user_id = '$userId' AND entry_id = '$entryId'";
            $r = mysqli_query ( $dbc, $q ) ;
            if (mysqli_num_rows($r) == 1) {
                $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                echo json_encode($row);
            }
        } else {
            echo "";
        } 
            
        
    }
               
    //get list of user's entries
    if ($_GET['action'] == 'getEntryList') {
        $entryList = array();
        $q = "SELECT entry_id, entry_title FROM Fractal_Entries WHERE user_id = '$userId' ORDER BY entry_id";
        $r = mysqli_query ( $dbc, $q ) ;
        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
               $entryList[$row['entry_id']] = $row['entry_title'];
            }
        }
        //ChromePhp::log(json_encode($entryList));
        echo json_encode($entryList);
    }   
    
    //get list of current entry's keywords
    if ($_GET['action'] == 'getKeywordList') {
        $keywordList = array();
        $q = "SELECT fek.keyword_id, fk.keyword_name FROM Fractal_Entry_Keywords fek JOIN Fractal_Keywords fk ON fek.keyword_id = fk.keyword_id WHERE fek.entry_id = '$entryId' AND fk.user_id = '$userId' ORDER BY 2";
         //ChromePhp::log($q);
        $r = mysqli_query ( $dbc, $q ) ;
        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
               // ChromePhp::log(json_encode($row));
               $keywordList[$row['keyword_id']] = $row['keyword_name'];
            }
        }
        echo json_encode($keywordList);
    }   
    
    //get list of keywords not attached to this entry
    if ($_GET['action'] == 'getUnusedKeywordList') {
        $unusedKeywordList = array();
        $q = "SELECT fk.keyword_id, fk.keyword_name FROM Fractal_Keywords fk LEFT JOIN Fractal_Entry_Keywords fek ON fk.keyword_id = fek.keyword_id AND fek.entry_id = '$entryId' WHERE fk.user_id = '$userId' AND fek.keyword_id IS NULL ORDER BY 2"; 
        //ChromePhp::log($q);
        $r = mysqli_query ( $dbc, $q ) ;
        if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
               $unusedKeywordList[$row['keyword_id']] = $row['keyword_name'];
            }
        }
        echo json_encode($unusedKeywordList);
    }  
    
     //check if user already has keyword
    if ($_GET['action'] == 'checkKeyword') {
        $keyword = sanitizeString($dbc, $_GET["newKeyword"]);
        $q = "SELECT keyword_id FROM Fractal_Keywords WHERE user_id = '$userId' AND keyword_name = '$keyword'";
        $r = mysqli_query ( $dbc, $q ) ;
        $isInDB = '1';
        if (mysqli_num_rows($r) ==  0) {
             //ChromePhp::log('no returns');
            $isInDB = '0';
        }
        echo $isInDB;
    }
    
     //get keyword entry data
    if ($_GET['action'] == 'getKeyData') {
        $keywordId =    $_GET["keywordId"];
        $q = "SELECT link_desc FROM Fractal_Entry_Keywords WHERE keyword_id = '$keywordId' AND entry_id = '$entryId'";
        $r = mysqli_query ( $dbc, $q ) ;
        if (mysqli_num_rows($r) == 1) {
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            //ChromePhp::log($row['link_desc']);
            echo trim($row['link_desc']);
        }
    }
    
    //make sure keyword exists somewhere in the entry text already
    if ($_GET['action'] == 'getKeyInEntry') {
        $keyword =    $_GET["keyword"];
        $q = "SELECT entry_id FROM Fractal_Entries WHERE entry_id = '$entryId' AND entry_text LIKE '%$keyword%'";
        //ChromePhp::log($q);
        $r = mysqli_query ( $dbc, $q ) ;
        if (mysqli_num_rows($r) > 0) {
            $response = "true";
        } else {
            $response =  "false";
        }
        //ChromePhp::log($response);
        echo $response;   
    }
    
    
    
}

mysqli_close($dbc);

?>