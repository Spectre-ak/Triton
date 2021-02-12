<?php
if ($_COOKIE['status'] == '1') {
    //echo ("<script>alert('debug echo');</script>");
    $username = $_COOKIE['statusUsername'];
    $serverName = "xxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    $tsql= "SELECT orignalName,profilePhoto,coverPhoto,location FROM dbo.allUsersInfo WHERE username='$username'";
    $getResults = sqlsrv_query($conn, $tsql);
    if ($getResults == FALSE) {
        echo ("<script>alert('Some Error occured while profile loading');</script>");
        exit();
    }
    $name = "";
    $location = "";
    $profile_picture_link = "";
    $cover_photo_link = "";
    $row = sqlsrv_fetch_array($getResults);	
    $name = $row['orignalName'];
    $profile_picture_link = $row['profilePhoto'];
    $cover_photo_link = $row['coverPhoto'];
    $location = $row['location'];
    echo ("
            <script>
                window.onload=function(){
                document.getElementById('coverImgId').src='$cover_photo_link';
                document.getElementById('profileImgId').src='$profile_picture_link';
                document.getElementById('nameID').innerHTML='$name'+',';
                document.getElementById('locationid').innerHTML='$location';
                }
            </script>");
} else {
    echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
}
function loadAgain() {
    $username = $_COOKIE['statusUsername'];
    if ($username == "") {
        echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
    }
    $serverName = "xxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    $tsql= "SELECT orignalName,profilePhoto,coverPhoto,location FROM dbo.allUsersInfo WHERE username='$username'";
    $getResults = sqlsrv_query($conn, $tsql);
    if ($getResults == FALSE) {
        echo ("<script>alert('Some Error occured while profile loading');</script>");
        exit();
    }
    $name = "";
    $location = "";
    $profile_picture_link = "";
    $cover_photo_link = "";
    $row = sqlsrv_fetch_array($getResults);	
    $name = $row['orignalName'];
    $profile_picture_link = $row['profilePhoto'];
    $cover_photo_link = $row['coverPhoto'];
    $location = $row['location'];
    echo ("
            <script>
                window.onload=function(){
                document.getElementById('coverImgId').src='$cover_photo_link';
                document.getElementById('profileImgId').src='$profile_picture_link';
                document.getElementById('nameID').innerHTML='$name'+',';
                document.getElementById('locationid').innerHTML='$location';
                }
            </script>");
}
?>
<?php
function compressImage($source, $destination, $quality) {
    // Get image info
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];
    // Create a new image from file
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
        break;
        case 'image/png':
            ///echo("asdasd");
            $image = imagecreatefrompng($source);
            imagepng($image, $destination, $quality);
        break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            imagegif($image, $destination, $quality);
        break;
        default:
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
    }
    // Return compressed image
    return $destination;
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $imgTemp = $_FILES["fileToUpload"]["tmp_name"];
    $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    echo $ext;
    if ($ext == 'PNG' || $ext == 'png') {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $compressedImage = compressImage($imgTemp, $target_file, 45);
        if ($compressedImage) {
            $status = 'success';
            $statusMsg = "Image compressed successfully.";
            echo $status;
        } else {
            $statusMsg = "Image compress failed!";
        }
    }
    if (isset($_POST["submitCoverPic"])) {
        if ($_FILES["fileToUploadCover"]["size"] > 5000000) {
            echo "<script>alert('File too large');</script>";
            exit();
        }
        $username = $_COOKIE['statusUsername'];
        $target_dir = "uploads/" . $username . "cover";
        $target_file = $target_dir . basename($_FILES["fileToUploadCover"]["name"]);
        //echo ("<script>alert('$target_file');</script>");
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // echo ("<script>alert('sd');</script>");
        $imgTemp = $_FILES["fileToUploadCover"]["tmp_name"];
        $ext = pathinfo($_FILES["fileToUploadCover"]["name"], PATHINFO_EXTENSION);
        //   echo $ext;
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
        while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
            if ($row['username'] == $username || $row['email'] == $username) {
                if ($row['coverPhoto'] != 'css/images/cover.png') {
                    $imgOld = $row['coverPhoto'];
                    // echo ("<script>alert('$imgOld');</script>");
                    unlink($imgOld);
                }
                break;
            }
        }
        if ($ext == 'PNG' || $ext == 'png') {
            if (move_uploaded_file($_FILES["fileToUploadCover"]["tmp_name"], $target_file)) {
                /// echo "The file ". htmlspecialchars( basename( $_FILES["fileToUploadCover"]["name"])). " has been uploaded.";
                
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            $compressedImage = compressImage($imgTemp, $target_file, 40);
            if ($compressedImage) {
                $status = 'success';
                $statusMsg = "Image compressed successfully.";
                //echo $status;
                
            } else {
                // $statusMsg = "Image compress failed!";
                echo ("<script>alert('Some Error occured while profile loading');</script>");
                exit();
            }
        }
        $sql = "UPDATE dbo.allUsersInfo SET coverPhoto='$target_file' WHERE username='$username' ";
        $getResults = sqlsrv_query($conn, $sql);
        if ($getResults == FALSE) {
            echo ("<script>alert('Some Error occured while updating table name');</script>");
            return "exit";
        }
        loadAgain();
    }
    if (isset($_POST["location"])) {
        if ($_POST["location"] == "") {
            echo "<script>alert('Empty location');</script>";
        }
        $loc = $_POST["location"];
        //echo ("<script>alert('$loc');</script>");
        $username = $_COOKIE['statusUsername'];
        $serverName = "xxxxxxxxxxx";
        $connectionOptions = array("Database" => "xxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxxxx");
        $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
        $sql = "UPDATE dbo.allUsersInfo SET location='$loc' WHERE username='$username' ";
        $getResults = sqlsrv_query($conn, $sql);
        if ($getResults == FALSE) {
            echo ("<script>alert('Some Error occured while updating table name');</script>");
            return "exit";
        }
        loadAgain();
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
	<link rel="stylesheet" href="css/profile.css">
	<link rel="shortcut icon" href="css/images/logo.png" />
	<title>Update</title>
</head>

<body style="background-color: #131316;" id="bodyID">
	<div class="container" style="padding-top: 4px;padding-bottom: 10px; text-align: center;">
		<img id="logo" src="css/images/cover.png" width="200px" style="border-radius: 40px;">
	</div>
	<div class="container" style="color: white">
	</div>
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
		<style>
		</style>
		<div class="container">
			<br>
			<p>
				<a id="nameID">..., </a><i class="fa fa-map-marker" aria-hidden="true" style="color: #0c82e2;padding-left: 4px;"></i><a style="padding-left: 4px;" id="locationid"> ...</a>
			</p>
			<span style="padding: 4px; padding-top: 4px;">
				<button class="btn btn-primary" onclick="changeProfileImage();">Click to change the profile Image</button>
			</span>
			<form action="" method="post" enctype="multipart/form-data" id="formforCoverPicture" style="padding: 4px;">
				<p style="padding-top:4px ">Select image to upload as cover picture:</p>
				<input type="file" name="fileToUploadCover" id="fileToUploadCover">
				<input type="submit" value="Upload Image" name="submitCoverPic" class="btn btn-primary">
			</form>
			<form action="" method="post" id="formforLocation" style="padding: 4px;">
				<p style="padding-top:4px ">Update your location:</p>
				<input type="text" name="location" id="lcoationID" style="color:white; background-Color:#202223;outline:none">
				<input type="submit" value="Update" name="lcoationSubmit" class="btn btn-primary">
			</form>
			<span style="padding: 4px; padding-top: 4px;">
				<button class="btn btn-primary" onclick="editUserProfileDone();">Done</button>
			</span>
		</div>
	</div>
	<script>
	function editUserProfileDone() {
		window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
	}

	function changeProfileImage() {
		window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/cropImg.php';
	}

	function displayWindowSize() {
		// Get width and height of the window excluding scrollbars
		var w = window.innerWidth;
		var h = window.innerHeight;
		//
		// Display result inside a div element
		///document.getElementById("result").innerHTML = "Width: " + w + ", " + "Height: " + h;
		//alert(w+" "+h);
		document.getElementById("mainContentDiv").style.height = h - 59 + "px";
		//document.getElementById("mainContentDiv").style.maxHeight=h+"";
		if(w < 765) {
			document.getElementById("logo").style.width = "100px";
		} else {
			document.getElementById("logo").style.width = "200px";
		}
	}
	// Attaching the event listener function to window's resize event
	window.addEventListener("resize", displayWindowSize);
	// Calling the function for the first time
	displayWindowSize();
	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
