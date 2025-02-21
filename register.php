<?php 

require 'connection.php';


if (!isset($_POST['full_name']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_FILES)) {
	exit('Please complete the registration form!');
}

if ($user = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
	$user->bind_param('s', $_POST['email']);
	$user->execute();
	$user->store_result();
	if ($user->num_rows > 0) {
		echo 'Username exists, please choose another!';exit;
	} else {
        $fileTmpPath = $_FILES['file_upload']['tmp_name'];
        $upload_dir = "/uploads/";
        $ext = pathinfo($_FILES["file_upload"]["name"], PATHINFO_EXTENSION);
        $upload_file_dir = __DIR__ . $upload_dir . $_POST['full_name'] .'.'. $ext;

        if(move_uploaded_file($fileTmpPath, $upload_file_dir)) {
            echo 'File uploaded successfully.';
        }

        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $file_name = $_POST['full_name'] .'.'. $ext;

        $sql = "INSERT INTO accounts (full_name, email, password, file_name)
            VALUES ('$full_name', '$email', '$password', '$file_name')";

        if ($con->query($sql) === TRUE) {
            echo "<script>alert('New User created successfully!');</script>";
            echo "<script type='text/javascript'> document.location ='register.html'; </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
	}
	$user->close();
} else {
	echo "Error: " . $sql . "<br>" . $con->error;
}
$con->close();
?>