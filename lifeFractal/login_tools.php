<?php # LOGIN HELPER FUNCTIONS.

# Function to load specified or default URL.
function load( $page = 'login.php' )
{
  # Begin URL with protocol, domain, and current directory.
  $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

  # Remove trailing slashes then append page name to URL.
  $url = rtrim( $url, '/\\' ) ;
  $url .= '/' . $page ;

  # Execute redirect then quit. 
  header( "Location: $url" ) ; 
  exit() ;
}

# Function to check email address and password. 
function validate( $dbc, $username = '', $pwd = '')
{
  # Initialize errors array.
  $errors = array() ; 

  # Check email field.
  if ( empty( $username ) ) 
  { $errors[] = 'Enter your username.' ; } 
  else  { $n = mysqli_real_escape_string( $dbc, trim( $username ) ) ; }

  # Check password field.
  if ( empty( $pwd ) ) 
  { $errors[] = 'Enter your password.' ; } 
  else { $p = mysqli_real_escape_string( $dbc, trim( $pwd ) ) ; }

  # On success retrieve user_id, first_name, and last name from 'users' database.
  if ( empty( $errors ) ) 
  {
    $q = "SELECT user_id, username FROM Fractal_Users WHERE username='$n' AND user_pw=SHA1('$p')" ;  
    $r = mysqli_query ( $dbc, $q ) ;
    if ( @mysqli_num_rows( $r ) == 1 ) 
    {
      $row = mysqli_fetch_array ( $r, MYSQLI_ASSOC ) ;
      return array( true, $row ) ; 
    }
    # Or on failure set error message.
    else { $errors[] = 'Username and password combination not found.' ; }
  }
  # On failure retrieve error message/s.
  return array( false, $errors ) ; 
}