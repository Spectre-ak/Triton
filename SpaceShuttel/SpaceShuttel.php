<link rel="shortcut icon" href="css/images/logo.png" /> 
<?php
if ($_COOKIE['status'] == '1') {
} else {
    echo ("
        <script>
        window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
        </script>");
}
$username = $_COOKIE['statusUsername'];
$serverName = "xxxxxxxxxxx";
$connectionOptions = array("Database" => "xxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxxxx");
$conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
$tsql = "SELECT orignalName,profilePhoto FROM dbo.allUsersInfo WHERE username='$username'";
$getResults = sqlsrv_query($conn, $tsql);
if (!$getResults) {
    $msg['msg'] = "serverError";
    exit();
}
$row = sqlsrv_fetch_array($getResults);
$profile = $row['profilePhoto'];
$name = $row['orignalName'];
echo (" 
		<script>
		var thisUserName='$username';
		var thisUserProfileLink='$profile';
		var thisUserOrignalName='$name';
		</script>");
$json_obf_posts = file_get_contents("networkPosts.json");
$data_posts = json_decode($json_obf_posts, true);
$arrrayLikes = array();
foreach ($data_posts as $key => $value) {
    $arrrayLikes[$key] = $value['likes'];
} 
arsort($arrrayLikes);
foreach ($arrrayLikes as $key => $value) {
    $user = $data_posts[$key]["user"];
    $image = $key;
    $title = $data_posts[$key]["title"];
    $desc = $data_posts[$key]["desc"];
    $likes = $data_posts[$key]["likes"];
    if ($user != "NASAAPIs") {
        echo "<a id=$user-title style='display:none'>$title</a>";
        echo "<a id=$user-desc style='display:none'>$desc</a>";
    } else {
        echo "<a id=$image-title style='display:none'>$title</a>";
        echo "<a id=$image-desc style='display:none'>$desc</a>";
    }
}
echo ("
        <script>
       	window.onload=function(){
         
        ");
foreach ($arrrayLikes as $key => $value) {
    //echo $key ." ". $value;
    //echo "<br>";
    //print_r( $data_posts[$key]);
    $user = $data_posts[$key]["user"];
    //$user=strval($user);
    $image = $key; //$image=strval($image);
    $title = $data_posts[$key]["title"]; //$title=strval($title);
    $desc = $data_posts[$key]["desc"]; //=strval($desc);
    $likes = $data_posts[$key]["likes"];
    if ($user != "NASAAPIs") {
        $tsql = "SELECT orignalName,profilePhoto FROM dbo.allUsersInfo WHERE username='$user'";
        $getResults = sqlsrv_query($conn, $tsql);
        if (!$getResults) {
            $msg['msg'] = "serverError";
            exit();
        }
        $row = sqlsrv_fetch_array($getResults);
        //function showUsersPost(usernamePost,name,profile,image,titleText,descText,likesOnThePost){
        $proLink = $row['profilePhoto'];
        $orignalName = $row['orignalName'];
        //echo("
        //   showUsersPost('$user','$orignalName','$proLink','$image','$title','$desc','$likes');
        //    ");
        echo ("
			var title=document.getElementById('$user-title').innerHTML;
			var desc=document.getElementById('$user-desc').innerHTML;
			document.getElementById('$user-title').remove();
			document.getElementById('$user-desc').remove();
			showUsersPost('$user','$orignalName','$proLink','$image',title,desc,'$likes');               
	      ");
    } else {
        $proLink = "";
        $orignalName = "NASA APIs";
        echo ("
			var title=document.getElementById('$image-title').innerHTML;
			var desc=document.getElementById('$image-desc').innerHTML;
			document.getElementById('$image-title').remove();
			document.getElementById('$image-desc').remove();
			showUsersPost('$user','$orignalName','$proLink','$image',title,desc,'$likes');               
	      ");
    }
}
echo ("}
    </script>");
?>
<script>
//function to add the all post from the networkPosts file---
//@params
//usernamePost=username of the post owner
//name=name of above user
//profile=avatar link of the above user
//image=image link of the post
//titleText=title of the post
//descText=description of the post
//likesOnThePost=likes on this post
function showUsersPost(usernamePost, name, profile, image, titleText, descText, likesOnThePost) {
	if(usernamePost == "NASAAPIs") {
		var nasaObj = {};
		nasaObj['title'] = titleText;
		nasaObj['image'] = image;
		nasaObj['desc'] = descText;
		addAPIsPosts(nasaObj, likesOnThePost, true);
	} else {
		var wholePost = document.createElement('div');
		wholePost.className = "container";
		var p2 = document.createElement('p');
		var img = document.createElement('IMG');
		img.setAttribute('src', profile);
		img.setAttribute('width', '40px');
		img.setAttribute('height', '40px');
		img.setAttribute('style', 'margin-right:40px');
		img.setAttribute('style', 'border-radius:40px 40px 40px 40px');
		img.setAttribute('alt', 'Avatar');
		p2.appendChild(img);
		p2.style.paddingTop = "5px";
		var a = document.createElement('a');
		a.style.paddingLeft = '6px';
		a.innerHTML = name;
		p2.appendChild(a);
		wholePost.appendChild(p2);
		//  <p align="center"><img id="output" class="img-fluid" style="max-height: 650px; width: auto " /></p>
		var title = document.createElement("h5");
		title.innerHTML = titleText;
		wholePost.appendChild(title);
		//console.log(response["image"]); 
		var postImage = document.createElement('p');
		postImage.align = "center";
		postImage.innerHTML = "<img id='output' src='uploads/" + image + "'class='img-fluid' style='max-height: 650px; width: auto'/>"
		wholePost.appendChild(postImage);
		var readMore = document.createElement("summary");
		readMore.style.outline = "none";
		readMore.innerHTML = "Read More";
		var info = document.createElement("details");
		info.appendChild(readMore);
		info.appendChild(document.createTextNode(descText));
		wholePost.appendChild(info);
		var line = document.createElement("hr");
		line.style.color = "#0c82e2";
		line.style.backgroundColor = "#0c82e2";
		//making the like button 
		var ele1 = document.createElement("a");
		ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i>";
		var like = document.createElement("a");
		like.innerHTML = likesOnThePost;
		like.style.paddingLeft = "5px";
		var ele1Andlike = document.createElement("p");
		ele1Andlike.appendChild(ele1);
		ele1Andlike.appendChild(like);
		var object = {};
		object['image'] = image;
		object['title'] = titleText;
		object['desc'] = descText;
		ele1Andlike.onclick = function(e) {
			ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' style='color: #0c82e2' aria-hidden='true'></i>";
			giveLikeToThisPost(usernamePost, object, like)
		};
		wholePost.appendChild(ele1Andlike);
		wholePost.appendChild(line);
		document.getElementById("allUsersPostDiv").appendChild(wholePost);
	}
}
</script>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
	<title>Space Shuttel</title>
</head>

<body style="background-color: #131316;">
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
				<a class="nav-link active" href="#peter" role="tab" data-toggle="tab" id="1" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/SpaceShuttel.php';"><i class="fa fa-home" style="color: #0c82e2"></i> Space Shuttel</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#danny" role="tab" data-toggle="tab" id="2" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/ExtraterrestrialLives.php';"><i class="fa fa-users" aria-hidden="true"></i> Astronauts</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#alberto" role="tab" data-toggle="tab" id="4" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/Radio.php';"><i class="fa fa-comments" aria-hidden="true"></i> Radio</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#agumbe" role="tab" data-toggle="tab" id="3" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
			</li>
		</ul>
	</div>
	<br>
	<div>
		<div class="container" id="mainContentDiv">
			<details id="a" style="border: 2px solid #0c82e2; border-radius:15px; padding: 6px;">
				<summary style="outline: none" id="detailsId">Add posts</summary>
				<p><input type="file" accept="image/*" name="file" id="file" onchange="loadFile(event)" style="display: none;"></p>
				<a style="border: 2px solid #0c82e2; border-radius:6px; padding: 6px;">
					<label for="file" style="cursor: pointer;">Upload Image <i class="fa fa-image fa-lg " aria-hidden="true" style="color: #"></i></label></a>
				<h5 id="titleDisplay" style="color: white"></h5>
				<p align="center"><img id="output" class="img-fluid" style="max-height: 650px; width: auto " /></p>
				<a id="descDisplay"></a>
				<br>
				<div id="infoDiv" style="padding: 4px;display: none;">
					<br>
					<input class="form-control border-secondary py-2" id="title" type="text" placeholder="Type title..." style="color:white; border-radius:40px;background-color:#131316; ">
					<br>
					<input class="form-control border-secondary py-2" id="desc" type="text" placeholder="Type discription..." style="color:white; border-radius:40px;background-color:#131316; ">
					<br>
					<p align="center"> <button id="uploadButton" class="btn primary" style="color: white; background-color: #0c82e2 ">Upload</button></p>
					<div class="loader" id="loader" style="display: none"></div>
				</div>
				<script>
				var loadFile = function(event) {
					var image = document.getElementById('output');
					image.src = URL.createObjectURL(event.target.files[0]);
					document.getElementById("infoDiv").style.display = "block";
				};
				document.getElementById("title").addEventListener('input', function(ev) {
					//console.log(this.value);
					document.getElementById("titleDisplay").innerHTML = this.value;
				});
				document.getElementById("desc").addEventListener('input', function(ev) {
					//console.log(this.value);
					document.getElementById("descDisplay").innerHTML = this.value;
				});
				document.getElementById("uploadButton").addEventListener('click', function(e) {
					document.getElementById("loader").style.display = "block";
					document.getElementById("uploadButton").disabled = true;
					var fd = new FormData();
					var files = $('#file')[0].files[0];
					fd.append('file', files);
					fd.append('title', document.getElementById("titleDisplay").innerHTML);
					fd.append('desc', document.getElementById("descDisplay").innerHTML);
					$.ajax({
						url: 'SpaceShuttelUploadingPostScript.php',
						type: 'post',
						data: fd,
						contentType: false,
						processData: false,
						success: function(response) {
							//console.log(response);
							if(response == "Sorry, there was an error uploading your file.") alert("someErrorOccured503");
							var postObjectToParse = {};
							var obj = JSON.parse(response);
							addThisUserPost(obj);
							document.getElementById("loader").style.display = "none";
							document.getElementById("uploadButton").disabled = false;
							document.getElementById("detailsId").click();
							document.getElementById("output").src = "";
							//alert(response);
						},
					});
				});

				function addThisUserPost(response) {
					//console.log(response);
					var wholePost = document.createElement('div');
					wholePost.className = "container";
					var p2 = document.createElement('p');
					var img = document.createElement('IMG');
					img.setAttribute('src', thisUserProfileLink);
					img.setAttribute('width', '40px');
					img.setAttribute('height', '40px');
					img.setAttribute('style', 'margin-right:40px');
					img.setAttribute('style', 'border-radius:40px 40px 40px 40px');
					img.setAttribute('alt', 'Avatar');
					p2.appendChild(img);
					p2.style.paddingTop = "5px";
					var a = document.createElement('a');
					a.style.paddingLeft = '6px';
					a.innerHTML = thisUserOrignalName;
					p2.appendChild(a);
					wholePost.appendChild(p2);
					//  <p align="center"><img id="output" class="img-fluid" style="max-height: 650px; width: auto " /></p>
					var title = document.createElement("h5");
					title.innerHTML = response['title'];
					wholePost.appendChild(title);
					console.log(response["image"]);
					var postImage = document.createElement('p');
					postImage.align = "center";
					postImage.innerHTML = "<img id='output' src='uploads/" + response['image'] + "'class='img-fluid' style='max-height: 650px; width: auto'/>"
					wholePost.appendChild(postImage);
					var readMore = document.createElement("summary");
					readMore.style.outline = "none";
					readMore.innerHTML = "Read More";
					var info = document.createElement("details");
					info.appendChild(readMore);
					info.appendChild(document.createTextNode(response['desc']));
					wholePost.appendChild(info);
					var line = document.createElement("hr");
					line.style.color = "#0c82e2";
					line.style.backgroundColor = "#0c82e2";
					//making the like button 
					var ele1 = document.createElement("a");
					ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i>";
					var like = document.createElement("a");
					like.innerHTML = " 0";
					like.style.paddingLeft = "5px";
					var ele1Andlike = document.createElement("p");
					ele1Andlike.appendChild(ele1);
					ele1Andlike.appendChild(like);
					ele1Andlike.onclick = function(e) {
						ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' style='color: #0c82e2' aria-hidden='true'></i>";
						giveLikeToThisPost(thisUserName, response, like)
					};
					wholePost.appendChild(ele1Andlike);
					wholePost.appendChild(line);
					document.getElementById("divForThisUserPosts").appendChild(wholePost);
				}

				function giveLikeToThisPost(usernameOfThePost, postObject, element) {
					//console.log("debug chekl");
					//console.log(postObject);
					var fd = new FormData();
					fd.append('image', postObject['image']);
					fd.append('title', postObject['title']);
					fd.append('desc', postObject['desc']);
					fd.append('usernameOfThePost', usernameOfThePost);
					$.ajax({
						url: 'LikeApost.php',
						type: 'post',
						data: fd,
						contentType: false,
						processData: false,
						success: function(response) {
							//console.log(response); 
							if(response != "error") {
								var no = element.innerHTML;
								no++;
								element.innerHTML = no;
							}
						},
					});
				}
				</script>
			</details>
			<br>
			<div id="divForThisUserPosts">
			</div>
			<div id="allUsersPostDiv"></div>
			<script>
			var apiArray = ["XXXXXX", "XXXXXXXX", "XXXX", "XXXXXXX"];

			function getRandomInt(min, max) {
				min = Math.ceil(min);
				max = Math.floor(max);
				return Math.floor(Math.random() * (max - min + 1)) + min;
			}

			function getdate() {
				var year = getRandomInt(1999, 2020);
				var month1 = getRandomInt(1, 12);
				var date = getRandomInt(1, 28);
				var month = "0";
				month1 = month1.toString();
				// console.log(month1);
				date = date.toString();
				if(month1.length == 1) {
					// console.log(month1);
					month = "0" + month1 + "";
				} else month = month1;
				if(date.length == 1) {
					date = "0" + date;
				}
				var datet = year + "-" + month + "-" + date;
				return datet;
			}

			function LoadNasaAPIs() {
				//console.log(getRandomInt(0,4));
				var apiKey = apiArray[getRandomInt(0, 3)];
				var datet = getdate();
				var req = new XMLHttpRequest();
				var url = "https://api.nasa.gov/planetary/apod?api_key=";
				req.open("GET", url + apiKey + "&date=" + datet);
				req.send();
				req.addEventListener("load", function() {
					if(req.status == 200 && req.readyState == 4) {
						var response = JSON.parse(req.responseText);
						var title = response.title;
						var imgU = response.url;
						var info = response.explanation;
						if(!imgU.includes("youtube") && !imgU.includes("player")) {
							var objectAPI = {};
							objectAPI['title'] = title;
							objectAPI['image'] = imgU;
							objectAPI['desc'] = info;
							addAPIsPosts(objectAPI, 0);
						}
					}
				});
			}

			function addAPIsPosts(response, noOfLikes, conditionForAppendingInAllusersDivOrAfterScrollingdiv) {
				//console.log(response);
				var wholePost = document.createElement('div');
				wholePost.className = "container";
				var p2 = document.createElement('p');
				var img = document.createElement('IMG');
				img.setAttribute('src', "https://api.nasa.gov/assets/footer/img/favicon-192.png");
				img.setAttribute('width', '40px');
				img.setAttribute('height', '40px');
				img.setAttribute('style', 'margin-right:40px');
				img.setAttribute('style', 'border-radius:40px 40px 40px 40px');
				img.setAttribute('alt', 'Avatar');
				p2.appendChild(img);
				p2.style.paddingTop = "5px";
				var a = document.createElement('a');
				a.style.paddingLeft = '6px';
				a.innerHTML = "NASA APIs";
				p2.appendChild(a);
				wholePost.appendChild(p2);
				//  <p align="center"><img id="output" class="img-fluid" style="max-height: 650px; width: auto " /></p>
				var title = document.createElement("h5");
				title.innerHTML = response['title'];
				wholePost.appendChild(title);
				//console.log(response["image"]); 
				var postImage = document.createElement('p');
				postImage.align = "center";
				postImage.innerHTML = "<img id='output' src='" + response['image'] + "'class='img-fluid' style='max-height: 650px; width: auto'/>"
				wholePost.appendChild(postImage);
				var readMore = document.createElement("summary");
				readMore.style.outline = "none";
				readMore.innerHTML = "Read More";
				var info = document.createElement("details");
				info.appendChild(readMore);
				info.appendChild(document.createTextNode(response['desc']));
				wholePost.appendChild(info);
				var line = document.createElement("hr");
				line.style.color = "#0c82e2";
				line.style.backgroundColor = "#0c82e2";
				//making the like button 
				var ele1 = document.createElement("a");
				ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' aria-hidden='true'></i>";
				var like = document.createElement("a");
				like.innerHTML = noOfLikes;
				like.style.paddingLeft = "5px";
				var ele1Andlike = document.createElement("p");
				ele1Andlike.appendChild(ele1);
				ele1Andlike.appendChild(like);
				ele1Andlike.onclick = function(e) {
					ele1.innerHTML = "<i class='fa fa-thumbs-up fa-lg' style='color: #0c82e2' aria-hidden='true'></i>";
					giveLikeToThisPostAPI(response, like)
				};
				wholePost.appendChild(ele1Andlike);
				wholePost.appendChild(line);
				if(conditionForAppendingInAllusersDivOrAfterScrollingdiv) document.getElementById("allUsersPostDiv").appendChild(wholePost);
				else document.getElementById("apisPostLocation").appendChild(wholePost);
			}

			function giveLikeToThisPostAPI(postObject, element) {
				//console.log("debug chekl");
				console.log(postObject);
				var fd = new FormData();
				fd.append('image', postObject['image']);
				fd.append('title', postObject['title']);
				fd.append('desc', postObject['desc']);
				$.ajax({
					url: 'LikeAPIPost.php',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response) {
						console.log(response);
						if(response != "error") {
							var no = element.innerHTML;
							no++;
							element.innerHTML = no;
						}
					},
				});
			}
			document.getElementById("mainContentDiv").onscroll = function() {
				var scrollDiff = this.scrollTop - (this.scrollHeight - this.offsetHeight);
				scrollDiff = Math.abs(scrollDiff);
				if(scrollDiff < 3) {
					LoadNasaAPIs();
				}
			};
			</script>
			<div>
			</div>
			<div id="apisPostLocation">
			</div>
			<div class="loader" id="loader" style="display:"></div>
		</div>
	</div>
	<style>
	3:active {
		background-color: red;
	}

	.loader {
		margin: 0 auto;
		border: 5px solid #f3f3f3;
		/* Light grey */
		border-top: 5px solid #3498db;
		/* Blue */
		border-radius: 50%;
		width: 60px;
		height: 60px;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
	</style>
	<script>
	// Defining event listener function
	function displayWindowSize() {
		// Get width and height of the window excluding scrollbars
		var w = window.innerWidth;
		var h = window.innerHeight;
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

	::-webkit-scrollbar {
		width: 3px;
		border-radius: 10px;
		opacity: 0.0;
		background-color: transparent;
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
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
