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
    $name = "";
    $location = "";
    $profile_picture_link = "";
    $cover_photo_link = "";
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        if ($row['username'] == $username || $row['email'] == $username) {
            $name = $row['orignalName'];
            $profile_picture_link = $row['profilePhoto'];
            $cover_photo_link = $row['coverPhoto'];
            $location = $row['location'];
            break;
        }
    }
    echo ("
            <script>
              window.onload=function(){
               document.getElementById('uploaded_image').src='$profile_picture_link';
                
               }
            </script>");
} else {
    echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
}
?>


<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>ProfileImage</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
	<script src="https://unpkg.com/dropzone"></script>
	<script src="https://unpkg.com/cropperjs"></script>
	<link rel="shortcut icon" href="css/images/logo.png" />
	<style>
	.image_area {
		position: relative;
	}

	img {
		display: block;
		max-width: 100%;
	}

	.preview {
		overflow: hidden;
		width: 160px;
		height: 160px;
		margin: 10px;
		border: 1px solid red;
	}

	.modal-lg {
		max-width: 1000px !important;
	}

	.overlay {
		position: absolute;
		bottom: 10px;
		left: 0;
		right: 0;
		background-color: rgba(255, 255, 255, 0.5);
		overflow: hidden;
		height: 0;
		transition: .5s ease;
		width: 100%;
	}

	.image_area:hover .overlay {
		height: 50%;
		cursor: pointer;
	}

	.text {
		color: #333;
		font-size: 20px;
		position: absolute;
		top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		text-align: center;
	}
	</style>
</head>

<body style="background-color: #131316;" id="bodyID">
	<div align="center" class="container" style="text-align:center">
		<img id="logo" src="css/images/cover.png" width="200px" style="border-radius: 40px;">
	</div>
	<div class="container" align="center">
		<br />
		<div class="row">
			<div class="col-md-4">&nbsp;</div>
			<div class="col-md-4">
				<div class="image_area">
					<form method="post">
						<label for="upload_image">
							<img src="css/images/profile_picture.png" id="uploaded_image" class="img-responsive img-circle" />
							<div align="center" style="color:white"> Click image to Change Profile Image </div>
							<input type="file" name="image" class="image" id="upload_image" style="display:none" />
						</label>
					</form>
				</div>
			</div>
			<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="background-color: #131316;">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content" style="background-color: #131316;">
						<div class="modal-header">
							<h5 class="modal-title" style="padding-top:4px; color:white;">Crop Image Before Upload</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true" styke="color:white;">×</span>
							</button>
						</div>
						<div class="modal-body" style="background-color: #131316;">
							<div class="img-container">
								<div class="row" style="background-color: #131316;">
									<div class="col-md-8">
										<img src="" id="sample_image" />
									</div>
									<div class="col-md-4">
										<div class="preview"></div>
									</div>
								</div>
							</div>
						</div>
						<p align="right" id="wait" style="display:none">Wait...</p>
						<div class="modal-footer">
							<button type="button" id="crop" onclick="showWait();" class="btn btn-primary">Crop</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button onclick="goBack();" class="btn btn-primary">Done</button>
</body>

</html>
<script>
function showWait() {
	document.getElementById("wait").style.display = "block";
}

function goBack() {
	window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/update.php';
}
$(document).ready(function() {
	var $modal = $('#modal');
	var image = document.getElementById('sample_image');
	var cropper;
	$('#upload_image').change(function(event) {
		var files = event.target.files;
		var done = function(url) {
			image.src = url;
			$modal.modal('show');
		};
		if(files && files.length > 0) {
			reader = new FileReader();
			reader.onload = function(event) {
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});
	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview: '.preview'
		});
	}).on('hidden.bs.modal', function() {
		cropper.destroy();
		cropper = null;
	});
	$('#crop').click(function() {
		canvas = cropper.getCroppedCanvas({
			width: 400,
			height: 400
		});
		canvas.toBlob(function(blob) {
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function() {
				var base64data = reader.result;
				$.ajax({
					url: 'upl.php',
					method: 'POST',
					data: {
						image: base64data
					},
					success: function(data) {
						$modal.modal('hide');
						//location.reload();
						window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/cropImg.php';
						//$('#uploaded_image').attr('src', "uploads/1611250779.jpg");
					}
				});
			};
		});
	});
});
</script>


