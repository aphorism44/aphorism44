<?php # DISPLAY COMPLETE LOGIN PAGE.

# Display footer section.
include ( 'lifeHeader.php' ) ;

# Display any error messages if present.
if ( isset( $errors ) && !empty( $errors ) )
{
 echo '<p id="err_msg">Oops! There was a problem:<br>' ;
 foreach ( $errors as $msg ) { echo " - $msg<br>" ; }
echo 'Please try again.' ;
 //echo 'Please try again or <a href="register.php">Register</a></p>' ;
}
?>

<!-- Display body section. -->
<h1>Login</h1>
<form action="login_action.php" method="post">
<p>Username: <input type="text" name="username"> </p>
<p>Password: <input type="password" name="pass"></p>
<p><input type="submit" value="Login" ></p>
</form>

<?php 

# Display footer section.
include ( 'lifeFooter.php' ) ; 

?>
