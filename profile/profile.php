<?php
if ($_COOKIE['status'] == '1') {
    //echo ("<script>alert('debug echo');</script>");
    $username = $_COOKIE['statusUsername'];
    $serverName = "xxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    $tsql = "SELECT * FROM dbo.allUsersInfo";
    $getResults = sqlsrv_query($conn, $tsql);
    if ($getResults == FALSE) {
        echo ("<script>alert('Some Error occured while profile loading');</script>");
        exit();
    }
    ///$tableName="";
    $name = "";
    $followers = 0;
    $followings = 0;
    $posts = 0;
    $likedPosts = 0;
    $totalComments = 0;
    $location = "";
    $profile_picture_link = "";
    $cover_photo_link = "";
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        if ($row['username'] == $username || $row['email'] == $username) {
            $name = $row['orignalName'];
            $followers = $row['followers'];
            $followings = $row['followings'];
            $posts = $row['userPosts'];
            $likedPosts = $row['likedPosts'];
            $totalComments = $row['totalComments'];
            $profile_picture_link = $row['profilePhoto'];
            $cover_photo_link = $row['coverPhoto'];
            $location = $row['location'];
            break;
        }
    }
    //echo ("<script>alert('$name');</script>");
    if ($name == "") {
        setcookie('status', "", time() + (1), '/', 'sqltry3.azurewebsites.net');
        setcookie('statusUsername', "", time() + (1), '/', 'sqltry3.azurewebsites.net');
        echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
    }
    echo ("
        <script>
       	 window.onload=function(){
         
        ");
    echo ("
        document.getElementById('Followers').innerHTML='$followers';
        document.getElementById('FollowingID').innerHTML='$followings';
        document.getElementById('coverImgId').src='$cover_photo_link';
        document.getElementById('profileImgId').src='$profile_picture_link';
        document.getElementById('likedpostsID').innerHTML='$likedPosts';
        document.getElementById('postsID').innerHTML='$posts';
        ");
    echo ("document.getElementById('nameID').innerHTML=");
    echo (" \"$name\" ");
    echo (";");
    echo ("document.getElementById('locationid').innerHTML=");
    echo (" \"$location\" ");
    echo ("}
        </script>");
} else {
    echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
}
if (isset($_POST['logout'])) {
    setcookie('status', "", time() + (1), '/', 'sqltry3.azurewebsites.net');
    setcookie('statusUsername', "", time() + (1), '/', 'sqltry3.azurewebsites.net');
    echo ("
        <script>
        alert('fpormmetjt');
        </script>");
}
?>

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<!--<link rel="stylesheet" href="css/profile.css">-->
	<link rel="shortcut icon" href="css/images/logo.png" />
	<title>Profile</title>
</head>

<body style="background-color: #131316;" id="bodyID">
	<style>
	.nav-link {
		color: white;
	}

	.nav-item>a:hover {
		color: #0c82e2;
	}

	/*code to change background color*/
	.navbar-nav>.active>a {
		background-color: green;
		color: green;
	}
	</style>
	<div class="container" style="padding-top: 4px;padding-bottom: 10px; text-align: center;">
		<img id="logo" src="css/images/cover.png" width="200px" style="border-radius: 40px;">
	</div>
	<div class="container">
		<ul class="nav nav-tabs nav-justified">
			<li class="nav-item" style="background-color: #131316;">
				<a class="nav-link" href="#peter" role="tab" data-toggle="tab" id="1" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/SpaceShuttel.php';"><i class="fa fa-rocket" style="color: #0c82e2"></i> Space Shuttel</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#danny" role="tab" data-toggle="tab" id="2" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/ExtraterrestrialLives.php';"><i class="fa fa-users" aria-hidden="true"></i> Astronauts</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#alberto" role="tab" data-toggle="tab" id="4" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/Radio.php';"><i class="fa fa-comments" aria-hidden="true"></i> Radio</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link active" href="#agumbe" role="tab" data-toggle="tab" id="3" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
			</li>
		</ul>
	</div>
	<br>
	<div class="container" id="mainContentDiv">
		<div style="text-align: center; ">
			<div class="container">
				<!-- <img src="C:\Users\upadh\Desktop\app\uploads\cover.png"> -->
				<img src="" id="coverImgId" class="img-fluid" alt="Responsive image" style="max-height:400px;max-width:80%;">
				<div style="text-align: center;margin-top: -14%">
					<img src="" id="profileImgId" class="img-fluid" alt="Responsive image" height="25%" width="25%" style=" max-heigth:4px; border-radius:50% 50% 50% 50%;">
				</div>
			</div>
		</div>
		<div class="container1">
			<br>
			<p>
				<a id="nameID">..., </a><i class="fa fa-map-marker" aria-hidden="true" style="color: #0c82e2;padding-left: 4px;"></i><a style="padding-left: 4px;" id="locationid"> ...</a>
			</p>
			<p>
				<a>Followers <a id="Followers">...</a>, </a> <a> Following <a id="FollowingID">...</a> </a>
			</p>
			<p id="fakeID">Your Posts <a id="postsID">...</a>,</p>
			<p id="fakeIDLiked">Liked Posts <a id="likedpostsID">...</a>,</p>
			<button class="btn btn-primary" onclick="logUserOut();">Log out </button>
			<span style="padding: 4px; padding-top: 4px;">
				<button class="btn btn-primary" onclick="editUserProfile();">Edit Profile </button>
			</span>
		</div>
	</div>
	<script>
	function editUserProfile() {
		window.location.href = "./update.php"
	}

	function logUserOut() {
		var aa = "<form style='display:none;' method='POST' action='https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php'><input id='buttonforSubmit' type='submit' name='logout'></form>";
		document.getElementById("bodyID").innerHTML = aa;
		document.getElementById("buttonforSubmit").click();
	}
	// Defining event listener function
	function displayWindowSize() {
		// Get width and height of the window excluding scrollbars
		var w = window.innerWidth;
		var h = window.innerHeight;
		// Display result inside a div element
		///document.getElementById("result").innerHTML = "Width: " + w + ", " + "Height: " + h;
		//alert(w+" "+h);
		document.getElementById("mainContentDiv").style.height = h - 59 + "px";
		//document.getElementById("mainContentDiv").style.maxHeight=h+"";
		if(w < 765) {
			document.getElementById("1").innerHTML = "<i class='fa fa-rocket' style='color: #0c82e2'></i>";
			document.getElementById("2").innerHTML = "<i class='fa fa-users' style='color: #0c82e2'></i>";
			document.getElementById("3").innerHTML = "<i class='fa fa-user' style='color: #0c82e2'></i>";
			document.getElementById("4").innerHTML = "<i class='fa fa-comments' style='color: #0c82e2'></i>";
			document.getElementById("logo").style.width = "100px";
		} else {
			document.getElementById("1").innerHTML = "<i class='fa fa-rocket' style='color: #0c82e2'></i> Space Shuttel";
			document.getElementById("2").innerHTML = "<i class='fa fa-users' style='color: #0c82e2'></i> Astronauts";
			document.getElementById("3").innerHTML = "<i class='fa fa-user' style='color: #0c82e2'></i> Profile";
			document.getElementById("4").innerHTML = "<i class='fa fa-comments' style='color: #0c82e2'></i> Radio";
			document.getElementById("logo").style.width = "200px";
		}
	}
	// Attaching the event listener function to window's resize event
	window.addEventListener("resize", displayWindowSize);
	// Calling the function for the first time
	displayWindowSize();
	</script>
	<style>
	#mainContentDiv {
		color: white;
		width: 100%;
		height: 912px;
		/*border: 2px solid #34799b;*/
		border-radius: 29px;
		overflow-x: hidden;
		overflow-y: auto
	}

	/* width */
	::-webkit-scrollbar {
		width: 3px;
		border-radius: 10px;
		opacity: 0.0;
	}

	/* Track */
	::-webkit-scrollbar-track {
		background: #0b0b0c00;
		;
		border-radius: 10px;
	}

	/* Handle */
	::-webkit-scrollbar-thumb {
		background: #6c757d;
		border-radius: 10px;
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
		background: #555;
	}
	</style>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
