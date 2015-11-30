<?php
    function load($page = 'login.php') {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $url = rtrim($url, '/\\');
        $url .= '/' . $page;
        header("Location: $url");
        exit();
    }
    
    function validate($dbc, $username = '', $password = '') {
        $errors = array();
        
        if (empty($username)) {
            $errors[] = 'Enter your username.';
        } else {
            $u = mysqli_real_escape_string($dbc , trim($username));
        }
        
        if (empty($password)) {
            $errors[] = 'Enter your password.';
        } else {
            $p = mysqli_real_escape_string($dbc , trim($password));
        }
        
        if (empty($errors)) {
            $qry = "SELECT user_id FROM Data_Users WHERE username = '$u' AND password = SHA1('$p')";
            $id = mysqli_query($dbc, $qry);
            
            if (mysqli_num_rows($id) == 1) {
                $row = mysqli_fetch_array($id, MYSQLI_ASSOC);
                return array(true, $row);
            } else {
                //echo $qry;
                $errors[] = 'Username and password not found.';
            }   
        }
        
        return array(false, $errors);        
    }
    

?>