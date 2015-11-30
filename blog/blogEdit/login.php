<?php

    $page_title = 'login';
    include ('blogHeader.html');
    
    if ( isset($errors) && !empty($errors)) {
        echo '<p id="err_msg">We had the following error:<br>';
        foreach ($errors as $msg) {
            echo " - $msg<br>";
        }
        echo 'Try again at <a href="login.php">Login</a></p>';
    }
?>

<form action="login_action.php" method="post"> 
    <p>
        Username: <input type="text" name="username">
    </p>
    <p>
        Password: <input type="password" name="password">
    </p>
    <p>
        <input type="submit" value="Login">
    </p>
</form>

<?php
    include ('blogFooter.html');
?>

