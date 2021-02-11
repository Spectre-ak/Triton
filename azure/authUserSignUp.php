<?php 
	$username=$_POST['username'];
	$email=$_POST['email'];
	$password=$_POST['password'];
	$passwordRepeat=$_POST['password-repeat'];
	$name=$_POST['name'];
	if($password!=$passwordRepeat){
		echo "password mismatch try again";exit();
	}
	echo "<script>alert('$username'+' '+'$email'+' '+'$password'+' '+'$name'+' '+'$passwordRepeat');</script>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Redirecting
	</title>
	<link rel="shortcut icon" href="css/images/logo.png" />
</head>
<body style="background-color: #131316;">

</body>
</html>