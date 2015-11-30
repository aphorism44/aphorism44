<?php # DISPLAY COMPLETE LOGGED IN PAGE.

# Access session.
if (session_status() == PHP_SESSION_NONE) {
  session_start();
  }

# Redirect if not logged in.
if ( !isset( $_SESSION[ 'user_id' ] ) ) { require ( 'login_tools.php' ) ; load() ; }

# Set page title and display header section.
$page_title = 'Home' ;
include ( 'lifeHeader.php' ) ;

# Display body section.
echo "<h1>HOME</h1><p>You are now logged in, {$_SESSION['username']}. </p>";

# Display footer section.
include ( 'lifeFooter.php' ) ;
?>