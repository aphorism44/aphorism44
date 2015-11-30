<?php 
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  }

include('../include/config.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/tr/html4/strict.dtd">
<html lang="en">  
    <head>
        <title>Life Fractal Demo</title>
        <meta name="description" content="Home page for Aphorism 44 software." />
        <meta name="keywords" content="HTML5, JavaScript, PHP, browser games, computer science, programming" />
        <meta name="author" content="Dominic Jesse" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="../main.css" />
        <link rel="shortcut icon" href="../aph44.ico">
    </head>
    
