<?php

//common functions
$page_title = 'Login Beta Life Fractal' ;

function queryDatabase($dbc, $query) {
    $result = mysqli_query($dbc, $query ) or die(mysqli_error($dbc));
    return $result;
}

function sanitizeString($dbc, $string) {
    $string = trim($string);    
    $string = strip_tags($string);
    $string = htmlentities($string);
    $string = stripslashes($string);
    return mysqli_real_escape_string($dbc, $string);
}

function destroySession() {
    $_SESSION = array();
    
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time() - 2592000, '/');
    
    session_destroy();
}

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

?>