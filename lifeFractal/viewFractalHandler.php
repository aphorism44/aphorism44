<?php //process fractal page
require ( 'connect_db.php' ) ;
require('../include/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php');
require('../include/chromePHP.php');
include 'phpFunctions.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
  }

//ChromePhp::log('trying to grab session data...');

//ChromePhp::log($_SESSION['entry_id']);
$userId = $_SESSION['user_id'];
$entryId = $_SESSION['entry_id'];
if(isset($_SESSION['keyword_id']))
    $keywordId = $_SESSION['keyword_id'];
else 
	$keywordId = $_SESSION['keyword_id'] = -1;


//ChromePhp::log('$userId: ' + $userId);
//ChromePhp::log('$entryId: ' + $entryId);
//ChromePhp::log('$keywordId: ' + $keywordId);

if ($_GET) {
    
    //get previous entry
    if ($_GET['action'] == 'getPreviousEntry') {
        $q = "SELECT entry_id from Fractal_Entries WHERE user_id = '$userId' AND entry_id = (SELECT MAX(entry_id) from Fractal_Entries  WHERE user_id = '$userId' AND entry_id < '$entryId')";
       //ChromePhp::log($q);
       $r = mysqli_query ( $dbc, $q ) ;
       if (mysqli_num_rows($r) > 0) {
           $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
           $entryId = $row['entry_id'];
           $_SESSION['entry_id'] = $entryId;    
        } 
        echo $_SESSION['entry_id'];
    }
    
    //get next entry
    if ($_GET['action'] == 'getNextEntry') {
         $q = "SELECT entry_id from Fractal_Entries WHERE user_id = '$userId' AND entry_id = (SELECT MIN(entry_id) from Fractal_Entries  WHERE user_id = '$userId' AND entry_id > '$entryId')";
       //ChromePhp::log($q);
       $r = mysqli_query ( $dbc, $q ) ;
       if (mysqli_num_rows($r) > 0) {
           $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
           $entryId = $row['entry_id'];
           $_SESSION['entry_id'] = $entryId;    
        } 
        echo $_SESSION['entry_id'];
        
    }
    
    
    //get entry page
    if ($_GET['action'] == 'getEntryHTML') {
       $newEntryId = $_GET['entryId'];
       if ($newEntryId > 0) {
            $_SESSION['entry_id'] = $newEntryId;
            $entryId = $_SESSION['entry_id'];
       } else {
            $q = "SELECT entry_id FROM  Fractal_Entries WHERE user_id = '$userId' ORDER BY entry_id ASC LIMIT 1";
            $r = mysqli_query ( $dbc, $q ) ;
            if ( @mysqli_num_rows( $r ) == 1 )  {
              $entryA = mysqli_fetch_array ( $r, MYSQLI_ASSOC ) ;
              $entryB = array_values($entryA);
              $entryId = array_shift($entryB);
              $_SESSION['entry_id'] = $entryId;
            } 
       }
       //ChromePhp::log('$entryId: ' + $entryId);
       $keywordList = array();
       $q1 = "SELECT keyword_id, keyword_name FROM Fractal_Keywords WHERE user_id = '$userId'";
       $r1 = mysqli_query ( $dbc, $q1 ) ;
       if (mysqli_num_rows($r1) > 0) {
            while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
               $keywordList[$row['keyword_id']] = $row['keyword_name'];
            }
        }
        //ChromePhp::log($keywordList);
        
        $q2 = "SELECT entry_text,entry_date, entry_title FROM Fractal_Entries WHERE entry_id = '$entryId' AND user_id = '$userId'";
        $r2 = mysqli_query ( $dbc, $q2 ) ;
        if (mysqli_num_rows($r2) > 0) {
            $entryInfo = mysqli_fetch_array($r2, MYSQLI_ASSOC);
        }
        //ChromePhp::log($entryInfo);
        
        foreach ($keywordList as $key => $value) {
            //ChromePhp::log($value);
            $entryInfo = str_ireplace($value, "<a href='#' onClick='getKeywordHTML(" . $key . ");'>" . $value . "</a>", $entryInfo);
        }
        //ChromePhp::log($entryInfo);
        echo json_encode($entryInfo);
        
    }
    
    //get keyword page
    if ($_GET['action'] == 'getKeywordHTML') {
       $newKeywordId = $_GET['keywordId'];
       if ($newKeywordId > 0) {
            $_SESSION['keyword_id'] = $newKeywordId;
            $keywordId= $_SESSION['keyword_id'];
       }
        
       $keywordEntryList = array();
       $q = "SELECT entry_id, link_desc FROM Fractal_Entry_Keywords WHERE keyword_id = '$keywordId'";
       //ChromePhp::log($q);
       $r = mysqli_query ( $dbc, $q ) ;
       if (mysqli_num_rows($r) > 0) {
            while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
               $keywordEntryList[$row['entry_id']] =   "<a href='#' onClick='getEntryHTML(" . $row['entry_id'] . ");'>" . $row['link_desc']. "</a>";
            }
        }
       //ChromePhp::log($keywordEntryList);
       $finalHTML = array();
       array_push($finalHTML, "<ul>");
       foreach ($keywordEntryList as $key => $value) {
           array_push($finalHTML, "<li>" . $value . "</li>");
       }
       array_push($finalHTML, "</ul>");
       //ChromePhp::log($finalHTML);
       echo json_encode($finalHTML);
        
    }      
   
}
    

mysqli_close($dbc);

?>