<?php

require 'connection.php';

if ( !isset($_POST['email'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

if ($user = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
	$user->bind_param('s', $_POST['email']);
	$user->execute();
	$user->store_result();
	if ($user->num_rows > 0) {
        $user->bind_result($id, $password);
	    $user->fetch();

        if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            header('Location: dashboard.html');
        }
	} else {
        echo "<script>alert('Incorrect username and/or password!');</script>";
        echo "<script type='text/javascript'> document.location ='login.html'; </script>";
	}
	$user->close();
} else {
	echo "<script>alert('Incorrect username and/or password!');</script>";
    echo "<script type='text/javascript'> document.location ='login.html'; </script>";
}
$con->close();