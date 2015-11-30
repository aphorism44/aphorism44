<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require ('blogResources/connect_db.php');
        require ('login_tools.php');
        list($check, $data) = validate($dbc, $_POST['username'], $_POST['password']);
        if ($check) {
            session_start();
            $_SESSION['user_id'] = $data['user_id'];
            load ('blogMain.php');            
        } else {
            $errors = $data;
        }
        mysqli_close($dbc);
    }
    include('login.php');
?>