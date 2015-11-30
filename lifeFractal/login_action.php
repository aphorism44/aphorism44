<?php # PROCESS LOGIN ATTEMPT.

# Check form submitted.
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
{
  # Open database connection.
  require ( 'connect_db.php' ) ;

  # Get connection, load, and validate functions.
  require ( 'login_tools.php' ) ;
  require('../include/chromePHP.php');

  # Check login.
  list ( $check, $data ) = validate ( $dbc, $_POST[ 'username' ], $_POST[ 'pass' ] ) ;

  # On success set session data and display logged in page.
  if ( $check )   {
    # Access session.
   if (session_status() == PHP_SESSION_NONE) {
      session_start();
   }
    $_SESSION[ 'user_id' ] = $data[ 'user_id' ] ;
    $_SESSION[ 'username' ] = $data[ 'username' ] ;
    
    //grab the latest entryId, or set it to -1 (meaning no entry)
    $userId =  $_SESSION[ 'user_id' ];
    $q = "SELECT entry_id FROM  Fractal_Entries WHERE user_id = '$userId' ORDER BY entry_id ASC LIMIT 1";
    
    $r = mysqli_query ( $dbc, $q ) ;
      if ( @mysqli_num_rows( $r ) == 1 )  {
        $entryA = mysqli_fetch_array ( $r, MYSQLI_ASSOC ) ;
        $entryB = array_values($entryA);
        $entryId = array_shift($entryB);
      } else {
          $entryId = -1;
      }
     
    $_SESSION[ 'entry_id' ] = $entryId;
    
    //also record the login in the database
    $q = "INSERT INTO Fractal_Logins(user_id, login_datetime) VALUES('$userId', NOW())";
    $r = mysqli_query ( $dbc, $q ) ;
    
    load ( 'home.php' ) ;
  }
  # Or on failure set errors.
  else { $errors = $data; } 

  # Close database connection.
  mysqli_close( $dbc ) ; 
}

# Continue to display login page on failure.
include ( 'login.php' ) ;

?>