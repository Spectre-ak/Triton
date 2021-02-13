<link rel="shortcut icon" href="css/images/logo.png" />
<script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js"></script>
<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-database.js"></script>
<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-firestore.js"></script>
<!-- form to connect or disconnect from a user -->
<form action="ConnectOrDisconnectUser.php" method="POST" style="display:none;" id="formId">
	<input type="text" id="in" name="inputValue" value="xxxxxxxxxxx" />
</form>
<!-- form to get the sent or received connections  -->
<form action="GetSorRconns.php" method="POST" style="display:none;" id="formIdGet">
	<input type="text" id="inGet" name="inputValueGet" value="xxxxxxxxxxx" />
</form>
<!-- form to get the all users which are not in the connections  -->
<form action="GetALLWithoutConnections.php" method="POST" style="display:none;" id="formIdGetALL">
	<input type="text" id="inGetALL" name="inputValueGetALL" value="xxxxxxxxxxx" />
</form>
<!-- form to get the all users which are in the connections  -->
<form action="GetALLConnections.php" method="POST" style="display:none;" id="formIdGetALLConnections">
	<input type="text" id="inGetALLConnections" name="inputValueGetALLConnections" value="xxxxxxxxxxx" />
</form>
<!-- form to cancel the sent connection request  -->
<form action="CancelRequest.php" method="POST" style="display:none;" id="formIdCancelRequest">
	<input type="text" id="inCancelRequest" name="inputValueCancelRequest" value="xxxxxxxxxxx" />
</form>
<!-- form to accept or reject Request  -->
<form action="AcceptOrReject.php" method="POST" style="display:none;" id="formIdAcceptOrReject">
	<input type="text" id="inAcceptOrReject" name="inputValueAcceptorRejectUsername" value="xxxxxxxxxxx" />
</form>
<script>
var allUsersPRO = {};
var allUsers = [];
//main function for rendering the list of users from different tabs---Allusers,Connections,Sent,Received
function disp(flagForLoadSearchResultsOrLoadDefaultResult, pro, name, username, flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections) {
	// /flagForLoadSearchResultsOrLoadDefaultResult will be used to not append the search results in the main arrays which is allUsersPRO,allUsers
	//function sotres the result for search and update the UI for dsiplay the result
	//in LoadSearchResults the result is not append in new allUsersPRO && allUsers
	//in LoadDefaultResult the result is added the new allUsersPRO && allUsers
	//console.log(pro+" "+name+" "+username);
	var p2 = document.createElement('p');
	var img = document.createElement('IMG');
	if(flagForLoadSearchResultsOrLoadDefaultResult) {
		allUsers.push(username);
		allUsersPRO[username] = [pro, name];
	}
	img.setAttribute('src', pro);
	img.setAttribute('width', '40px');
	img.setAttribute('height', '40px');
	img.setAttribute('style', 'margin-right:40px');
	img.setAttribute('style', 'border-radius:40px 40px 40px 40px');
	img.setAttribute('alt', 'Avatar');
	if(flagForAcceptOrRejectConnections) {
		var ele = document.createElement('button');
		ele.id = "buttonID"
		ele.style.float = 'right';
		ele.className = 'btn btn-primary'
		ele.innerHTML = "Accept"
		ele.style.marginRight = "10px";
		var eleR = document.createElement('button');
		eleR.id = "buttonID"
		eleR.style.float = 'right';
		eleR.className = 'btn btn-primary'
		eleR.innerHTML = "Reject"
		eleR.paddingLeft = "40px";
		ele.onclick = function() {
			AcceptOrRejectRequest(username, this, eleR);
		};
		eleR.onclick = function() {
			AcceptOrRejectRequest(username, this, ele);
		};
		p2.appendChild(img);
		var a = document.createElement('a');
		a.style.paddingLeft = '6px';
		a.innerHTML = name;
		p2.appendChild(a);
		p2.appendChild(eleR);
		p2.appendChild(ele);
		document.getElementById('navTabContent').appendChild(p2);
	} else if(flagForSentConnections) {
		var ele = document.createElement('button');
		ele.id = "buttonID"
		ele.style.float = 'right';
		ele.innerHTML = 'Cancel';
		ele.className = 'btn btn-primary'
		ele.onclick = function() {
			cancelSentRequest(username, this);
		};
		p2.appendChild(img);
		var a = document.createElement('a');
		a.style.paddingLeft = '6px';
		a.innerHTML = name;
		p2.appendChild(a);
		p2.appendChild(ele);
		document.getElementById('navTabContent').appendChild(p2);
	} else {
		var ele = document.createElement('button');
		ele.id = "buttonID"
		ele.style.float = 'right';
		if(flagForConnectorDisconnect) ele.innerHTML = 'Connect';
		else ele.innerHTML = 'Disconnect';
		ele.className = 'btn btn-primary'
		ele.onclick = function() {
			submitForm(username, this);
		};
		p2.appendChild(img);
		var a = document.createElement('a');
		a.style.paddingLeft = '6px';
		a.innerHTML = name;
		p2.appendChild(a);
		if(flagForChat) {
			var span = document.createElement("span");
			span.style.float = "right";
			span.style.marginLeft = "15px";
			span.style.paddingTop = "10px";
			var icon = "<big><big><i href='#chat' class='fas fa-comment-alt fa-lg'></i></big></big>";
			span.innerHTML = icon;
			span.onclick = function() {
				chatWithUser(username, name)
			};
			p2.appendChild(span);
		}
		p2.appendChild(ele);
		document.getElementById('navTabContent').appendChild(p2);
	}
}
//to be used later for sorting based on the search result for the Followers and Following
var allUsersProFWFI = {};
</script>
<script>
function hideSearch() {
	document.getElementById("searchdiv").style.display = "none";
	document.getElementById("serchId").style.display = "block";
	//by exiting the search bar loading the allUsersPRO list as default
	document.getElementById("navTabContent").innerHTML = "";
	var flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections;
	//var flagForConnectorDisconnect,flagForChat,
	if(document.getElementById("IDAllUsers").className == "nav-link active") {
		flagForConnectorDisconnect = true;
		flagForChat = false;
		flagForAcceptOrRejectConnections = false;
		flagForSentConnections = false;
	} else if(document.getElementById("IDYourConnections").className == "nav-link active") {
		flagForConnectorDisconnect = false;
		flagForChat = true;
		flagForAcceptOrRejectConnections = false;
		flagForSentConnections = false;
	} else if(document.getElementById("IDSent").className == "nav-link active") {
		flagForConnectorDisconnect = false;
		flagForChat = false;
		flagForAcceptOrRejectConnections = false;
		flagForSentConnections = true;
	} else if(document.getElementById("IDReceived").className == "nav-link active") {
		flagForConnectorDisconnect = false;
		flagForChat = false;
		flagForAcceptOrRejectConnections = true;
		flagForSentConnections = false;
	}
	for(var key in allUsersPRO) {
		disp(false, allUsersPRO[key][0], allUsersPRO[key][1], key, flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections);
	}
	document.getElementById("InfoRes").innerHTML = "Available Users.";
}

function hideOrShowSearchBar() {
	if(document.getElementById("searchdiv").style.display == "none") {
		document.getElementById("serchId").style.display = "none";
		document.getElementById("searchdiv").style.display = "block";
	}
}

function LoadSent() {
	//GetSorRconns.php
	//function to get the sent connections
	document.getElementById("inGet").value = "Sent";
	//alert("sent");
	$("#formIdGet").submit();
}

function LoadReceived() {
	//GetSorRconns.php
	//fucntion to get the received connections
	document.getElementById("inGet").value = "Received";
	//alert("Received");
	$("#formIdGet").submit();
}

function LoadConnections() {
	//Loads all users which are in connections
	//GetALLConnections.php
	document.getElementById("inGetALLConnections").value = "ALLConnections";
	//alert("ALL");
	$("#formIdGetALLConnections").submit();
}

function LoadDefault() {
	//Loads all users which are not in your connection
	//GetALLWithoutConnections.php
	document.getElementById("inGetALL").value = "ALLWithoutConnections";
	//alert("ALL");
	$("#formIdGetALL").submit();
}
var typeOFOperationForSubmitForm = "";
var globalForConnectOrDisconnect;

function submitForm(username, ele) {
	//to connect or disconnect from a user
	//alert("ads");
	// /ConnectOrDisconnectUser.php
	document.getElementById("in").value = username;
	typeOFOperationForSubmitForm = ele.innerHTML;
	$("#formId").submit();
	globalForConnectOrDisconnect = ele;
	// console.log(ele);
	// console.log(ele.className);
	//ele.className="btn btn-primary disabled";
}
var ResultFromReceivedRequest;
var global1AcceptOrRejectRequestForDisable;
var global2AcceptOrRejectRequestForDisable;

function AcceptOrRejectRequest(username, element, secondRemain) {
	//if(element.innerHTML=="Accept") then accept the connection request form the user username
	//else reject the connection request from the user username
	//console.log(element.innerHTML);
	ResultFromReceivedRequest = element.innerHTML;
	document.getElementById("inAcceptOrReject").value = username;
	global1AcceptOrRejectRequestForDisable = element;
	global2AcceptOrRejectRequestForDisable = secondRemain;
	$("#formIdAcceptOrReject").submit();
	//in the end make the both the buttons element and secondRemain as disabled
}
var globalElementCancelRequest;

function cancelSentRequest(username, ele) {
	//cancel the request sent to this username 
	document.getElementById("inCancelRequest").value = username;
	globalElementCancelRequest = ele;
	$("#formIdCancelRequest").submit();
	//in the end make the ele as disabled
}
//xxxxxxxxxxxxxMessagingxxxxxxxxxxxxxxxxx
//The chatting Interface is here-----
var sendingReference, recevingReference;
var thisUserOrignalName, UserWithChattingOrignalName;
var thisUsername, ThisUserHistoryDatabase;
//ThisUserHistoryDatabase name must be unique for this status user and with all of its connections
var flagForNotLoadingHistoryAgain = true;
//vars for checking if a user is online or not
var statusThisUsername, statusUsername;
var currentUserStatusTime;

function chatWithUser(username, fullnameOFUser) {
	document.getElementById("navInnerId").style.display = "none";
	document.getElementById("searchSupportDiv").style.display = "none";
	document.getElementById("searchdiv").style.display = "none";
	document.getElementById("navTabContent").innerHTML = "";
	document.getElementById("MessagingInterface").style.display = "block";
	document.getElementById("nameUser").innerHTML = fullnameOFUser;
	document.getElementById("MessagingInterfaceCloseID").onclick = function() {
		document.getElementById("MessagingInterface").style.display = "none";
		window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/Radio.php';
	}
	//open the chatting inerface with the username as user
	var firebaseConfig = {
		apiKey: "xxxxxxxxxxxxxx",
		authDomain: "xxxxxxxxxxxx",
		databaseURL: "xxxxxxxxxxxxx",
		projectId: "xxxxxxxxxxxxxxxx",
		storageBucket: "xxxxxxxxxxxx",
		messagingSenderId: "xxxxxxxxxxxx",
		appId: "xxxxxxx"
	};
	// Initialize Firebase
	firebase.initializeApp(firebaseConfig);
	//setting up the status (online/offline)
	statusUsername = "status" + username;
	statusThisUsername = "status" + thisUserusername;
	var stsOtherUser = firebase.database().ref(statusUsername);
	stsOtherUser.on('value', function(snapshot) {
		var data = snapshot.val();
		currentUserStatusTime = data;
	});
	setInterval(function() {
		firebase.database().ref(statusThisUsername).set(Date.now());
		var res = currentUserStatusTime - parseInt(Date.now());
		if(Math.abs(res) > 6000) {
			document.getElementById("status").innerHTML = "offline"
			document.getElementById("status").style.color = "red";
		} else {
			document.getElementById("status").innerHTML = "online"
			document.getElementById("status").style.color = "green";
		}
	}, 4000);
	//done setting up the online/offline status 
	thisUsername = thisUserusername;
	thisUserOrignalName = thisUserOrignalName2;
	UserWithChattingOrignalName = fullnameOFUser;
	sendingReference = thisUsername + "to" + username;
	recevingReference = username + "to" + thisUsername;
	ThisUserHistoryDatabase = username + "History";
	var valsHist = firebase.database().ref(thisUsername).child(ThisUserHistoryDatabase);
	//var valsHist=firebase.database().ref("users");
	valsHist.on('value', function(snapshot) {
		if(flagForNotLoadingHistoryAgain) {
			var data = snapshot.val();
			//console.log(data);
			var tobeSorted = [];
			for(var key in data) {
				var arr = [];
				for(var key2 in data[key]) {
					arr.push(data[key][key2]);
				}
				tobeSorted.push(arr);
			}
			//console.log(tobeSorted);
			tobeSorted.sort(function(a, b) {
				//console.log(a[1]);console.log(b[1]);
				//console.log(a[1]>b[1]);
				if(a[3] > b[3]) return 1;
				if(a[3] < b[3]) return -1;
				return 0;
			});
			//console.log(tobeSorted);
			//sendAndReceiveMessageUIUpdate(usernameOrignalName,msg,time,flagForSendingOrReceiving)
			//messageArray={"message":messageToSend,"nameToDisplay":thisUserOrignalName,"OrignalTime":cleanedCurrtime,"timeToDisplay":time};
			//["Thu Feb 04 2021 23:10:20 ", "sdfdsf", "Skahsh", "23:10:20"]
			for(var i = 0; i < tobeSorted.length; i++) {
				var ar = tobeSorted[i];
				// console.log("ar is "+ar); 
				// console.log("usernameOrignalName "+ar[1]);
				//console.log("msg "+ar[2]);
				//console.log("time "+ar[3]); 
				if(ar[2] == thisUserOrignalName) //sent message
					sendAndReceiveMessageUIUpdate(ar[2], ar[1], ar[3], true);
				else sendAndReceiveMessageUIUpdate(ar[2], ar[1], ar[3], false);
			}
			flagForNotLoadingHistoryAgain = false;
		}
	});
	getMsgs();
}

function getMsgs() {
	var vals = firebase.database().ref(recevingReference);
	vals.on('value', function(snapshot) {
		//console.log("how many times executed "+data)
		var data = snapshot.val();
		//console.log(data);
		if(data != null) {
			//console.log("data is ------- "+data); 
			for(var key in data) {
				var messageObject = data[key];
				console.log("received object " + messageObject);
				messageObject = messageObject['msg'];
				console.log(messageObject);
				//firslty updating the current chat
				sendAndReceiveMessageUIUpdate(messageObject['nameToDisplay'], messageObject["message"], messageObject["timeToDisplay"], false);
				//secondly updating the history chat refrence
				firebase.database().ref(thisUsername).child(ThisUserHistoryDatabase).push().set(messageObject);
			}
			firebase.database().ref(recevingReference).remove();
		}
	});
}

function sendAndReceiveMessageUIUpdate(usernameOrignalName, msg, time, flagForSendingOrReceiving) {
	var msgComplete = document.createElement("p");
	var usernameSmallTag = usernameOrignalName.small();
	//console.log(time);
	time = time.toString();
	time = time.small();
	var a = document.createElement("a");
	a.innerHTML = usernameSmallTag.small() + " ";
	a.style.color = "#e4ff00"
	var msgElement = document.createElement("a");
	msgElement.innerHTML = msg;
	var timeSmall = document.createElement("a");
	timeSmall.innerHTML = " " + time.small();
	timeSmall.style.color = "#e4ff00"
	//msgComplete.appendChild(a); 
	msgComplete.appendChild(msgElement);
	msgComplete.appendChild(timeSmall);
	if(flagForSendingOrReceiving) msgComplete.style.textAlign = "right";
	document.getElementById("divForMessagingArea").appendChild(msgComplete);
	document.getElementById("divForMessagingArea").scrollTop = document.getElementById("divForMessagingArea").scrollHeight;
	if(flagForSendingOrReceiving) document.getElementById("inputSendMessage").value = "";
}

function sendMessage(argument) {
	//alert(currentUserName+" receving database is "+recevingReference+ " and sending datyabse ois "+sendingReference);
	//alert(document.getElementById("inputForTextDuringChat").value +" "+sendingReference+" rece is "+recevingReference)
	var messageToSend = document.getElementById("inputSendMessage").value;
	if(messageToSend.length == 0) alert("Invalid Message");
	else {
		//firebase.database().ref('AvailableChats').push().child("user").set(username);
		var Currtime = new Date();
		var cleanedCurrtimeArray = Currtime.toString().split("GMT");
		var cleanedCurrtime = cleanedCurrtimeArray[0];
		var time = Currtime.getHours() + ":" + Currtime.getMinutes() + ":" + Currtime.getSeconds();
		//firebase.database().ref("onGoingChats").child(sendingReference).push().child("msg").set(messageToSend+"!`!`1`1~~@@~"+time);
		var messageArray = {
			"message": messageToSend,
			"nameToDisplay": thisUserOrignalName,
			"OrignalTime": cleanedCurrtime,
			"timeToDisplay": time
		};
		//firslty updating the current chat
		firebase.database().ref(sendingReference).push().child("msg").set(messageArray);
		//secondly updating the history chat refrence
		firebase.database().ref(thisUsername).child(ThisUserHistoryDatabase).push().set(messageArray);
		sendAndReceiveMessageUIUpdate(thisUserOrignalName, messageToSend, time, true);
	}
}
//xxxxxxxxxxxxxMessagingxxxxxxxxxxxxxxxxx
</script>
<?php
if ($_COOKIE['status'] == '1') {
    //echo ("<script>alert('debug echo');</script>");
    $username = $_COOKIE['statusUsername'];
    $serverName = "xxxxxxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxx", "Uid" => "xxx", "PWD" => "xxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    $tsql = "SELECT * FROM dbo.allUsersInfo";
    $getResults = sqlsrv_query($conn, $tsql);
    if ($getResults == FALSE) {
        echo ("<script>alert('Some Error occured while profile loading');</script>");
        exit();
    }
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    $name = array();
    $userName = array();
    $location = array();
    $profile_picture_link = array();
    //$cover_photo_link="";
    $thisUserusername = $username;
    $thisUserOrignalName;
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        if ($row['username'] == $username || $row['email'] == $username) {
            $thisUserOrignalName = $row['orignalName'];
            continue;
        }
        if (in_array($row['username'], $data[$username]['Connections']) || in_array($row['username'], $data[$username]['ReceivedConnections']) || in_array($row['username'], $data[$username]['sentConnections'])) {
            continue;
        }
        array_push($name, $row['orignalName']);
        array_push($location, $row['location']);
        array_push($profile_picture_link, $row['profilePhoto']);
        array_push($userName, $row['username']);
    }
    echo ("
            ");
    echo ("
            <script>
              var thisUserusername='$thisUserusername';
              var thisUserOrignalName2='$thisUserOrignalName';
              window.onload=function(){
               
              ");
    //disp(pro,name,username,fagForlConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
    for ($i = 0;$i < count($name);$i++) {
        $pro = $profile_picture_link[$i];
        $nam = $name[$i];
        $usNam = $userName[$i];
        //  echo("
        //   disp(true,'$pro','$nam','$usNam',true,false,false,false);
        //  ");
        echo ("disp(");
        echo ('true');
        echo (',');
        echo (" \"$pro\" ");
        echo (',');
        echo (" \"$nam\" ");
        echo (',');
        echo (" \"$usNam\" ");
        echo (',');
        echo ('true');
        echo (',');
        echo ('false');
        echo (',');
        echo ('false');
        echo (',');
        echo ('false');
        echo (");");
    }
    echo ("}
           </script>");
} else {
    echo ("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
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
	<link rel="shortcut icon" href="css/images/logo.png" />
	<title>Radio</title>
</head>

<body style="background-color: #131316;  
  /*background-image: url('./default.png');
backdrop-filter: blur(10px);
*/
  ">
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
				<a class="nav-link active" href="#alberto" role="tab" data-toggle="tab" id="4" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/Radio.php';"><i class="fa fa-comments" aria-hidden="true"></i> Radio</a>
			</li>
			<li class="nav-item" style="background-color: #131316">
				<a class="nav-link" href="#agumbe" role="tab" data-toggle="tab" id="3" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
			</li>
		</ul>
	</div>
	<br>
	<style>
	</style>
	<div class="container">
		<div class="container" id="navTabForOptions">
			<nav class="navbar navbar navbar-expand-sm bg" id="navInnerId">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar">
						<i style="color: #0c82e2" class="fa fa-chevron-circle-down" aria-hidden="true"></i>
						<a id="defInfo" style="color:white"> All Users</a>
					</button>
					<div class="navbar-collapse justify-content-center collapse" id="Navbar">
						<ul class="nav nav-tabs justify-content-center">
							<li class="nav-item">
								<a class="nav-link active" onclick="document.getElementById('defInfo').innerHTML='All Users';LoadDefault();" id="IDAllUsers" data-toggle="tab" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> All Users</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" onclick="document.getElementById('defInfo').innerHTML=' Your Connections';LoadConnections();" id="IDYourConnections" data-toggle="tab" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> Your Connections</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" onclick="document.getElementById('defInfo').innerHTML=' Sent';LoadSent();" id="IDSent" data-toggle="tab" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> Sent</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" onclick="document.getElementById('defInfo').innerHTML=' Received';LoadReceived();" id="IDReceived" data-toggle="tab" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> Received</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
			<div style="padding-left: 10px;padding-top: 2px;" id="searchSupportDiv">
				<button onclick="hideOrShowSearchBar();" class="btn btn-secondary" id="serchId" style="background-color:  #131316;">
					<i class="fa fa-search" aria-hidden="true" style="color: #0c82e2"></i>
				</button>
			</div>
			<div style="display: table; margin-left:auto;text-align: left; display: none" id="searchdiv">
				<div class="input-group" style="padding: 4px;">
					<input class="form-control border-secondary py-2" id="searchID" type="search" placeholder="Type name..." style="border-radius:40px;">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" style="background-color: white;border-radius: 20px" onclick="hideSearch();">
							<i class="fa fa-times" aria-hidden="true" style="color: #0c82e2"></i>
						</button>
					</div>
				</div>
			</div>
			<p style="padding: 4px;padding-left: 10px" id="InfoRes"><big>Available Users</big></p>
			<div id="navTabContent">
				<!--<p> <img src="./uploads/default.png" alt="Avatar" height="40" width="40" style="border-radius:40px 40px 40px 40px"> Admin</p>
      -->
			</div>
			<p style="text-align:center">
			</p>
			<div class="loader"></div>
			<div id="MessagingInterface" style="display: none;">
				<div class="container" id="mainContentDiv">
					<big><i id="MessagingInterfaceCloseID" class="fa fa-arrow-left fa-lg" aria-hidden="true" style="color: #0c82e2;padding-right: 10px;"></i></big>
					<a id="nameUser">Name </a> <a style="padding-left: 5px;" id="status" style="color:red">offline</a>
					<div class="container" id="divForMessagingArea">
					</div>
					<p><input type="text" class="messageInput" id="inputSendMessage" name="usernameLogin" placeholder="Msg..." required>
						<big><i onclick="sendMessage();" class="fa fa-paper-plane fa-lg" aria-hidden="true" style="padding-left: 10px;"></i></big>
					</p>
				</div>
			</div>
		</div>
	</div>
	<style>
	3:active {
		background-color: red;
	}
	</style>
	<script>
	document.getElementById("searchID").addEventListener('input', function(e) {
		// console.log(document.getElementById("11").className);
		//console.log(document.getElementById("22").className);
		//console.log(document.getElementById("33").className);
		document.getElementById("navTabContent").innerHTML = "";
		var val = document.getElementById("searchID").value;
		//
		var flag = false; //boolean value for cheking weather the OtherPeople/Followers or Following is loaded.
		var flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections;
		//var flagForConnectorDisconnect,flagForChat,
		//disp(pro,name,username,flagForConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
		// if(document.getElementById("11").className=="nav-link active" || document.getElementById("22").className=="nav-link active")flag=true;
		if(document.getElementById("IDAllUsers").className == "nav-link active") {
			flagForConnectorDisconnect = true;
			flagForChat = false;
			flagForAcceptOrRejectConnections = false;
			flagForSentConnections = false;
		} else if(document.getElementById("IDYourConnections").className == "nav-link active") {
			flagForConnectorDisconnect = false;
			flagForChat = true;
			flagForAcceptOrRejectConnections = false;
			flagForSentConnections = false;
		} else if(document.getElementById("IDSent").className == "nav-link active") {
			flagForConnectorDisconnect = false;
			flagForChat = false;
			flagForAcceptOrRejectConnections = false;
			flagForSentConnections = true;
		} else if(document.getElementById("IDReceived").className == "nav-link active") {
			flagForConnectorDisconnect = false;
			flagForChat = false;
			flagForAcceptOrRejectConnections = true;
			flagForSentConnections = false;
		}
		if(val == "") {
			//load the whole list
			//allUsersPRO
			for(var key in allUsersPRO) {
				disp(false, allUsersPRO[key][0], allUsersPRO[key][1], key, flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections);
			}
			document.getElementById("InfoRes").innerHTML = "Available Users.";
		} else {
			val = val.toLowerCase();
			for(var key in allUsersPRO) {
				var name = allUsersPRO[key][1];
				name = name.toLowerCase();
				if(val.length > name.length) continue;
				if(name.substring(0, val.length) == val) {
					disp(false, allUsersPRO[key][0], allUsersPRO[key][1], key, flagForConnectorDisconnect, flagForChat, flagForAcceptOrRejectConnections, flagForSentConnections);
				}
			}
			if(document.getElementById("navTabContent").innerHTML == "") {
				document.getElementById("InfoRes").innerHTML = "No results found.";
			} else {
				document.getElementById("InfoRes").innerHTML = "Available Users.";
			}
		}
	});
	// Defining event listener function
	function displayWindowSize() {
		// Get width and height of the window excluding scrollbars
		var w = window.innerWidth;
		var h = window.innerHeight;
		// Display result inside a div element
		///document.getElementById("result").innerHTML = "Width: " + w + ", " + "Height: " + h;
		//alert(w+" "+h);
		//  alert(+" "+);
		document.getElementById("navTabForOptions").style.height = h - 59 + "px";
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
	#navTabForOptions {
		color: white;
		width: 100%;
		height: 10000px;
		/* border: 2px solid #34799b;*/
		border-radius: 29px;
		overflow-x: hidden;
		overflow-y: auto
	}

	#divForMessagingArea {
		color: white;
		width: 100%;
		height: 90%;
		border: 2px solid #34755b;
		border-radius: 29px;
		overflow-x: hidden;
		overflow-y: auto
	}

	.messageInput {
		color: white;
		background-color: #131316;
		width: 80%;
		height: 40px;
		padding: 1% 4% 1%;
		margin: 2px;
		border: 2px solid #0c82e2;
		border-radius: 20px;
	}

	.messageInput:hover,
	.messageInput:focus {
		background-color: #131316;
		box-shadow: 3px 3px 20px #11B2E8;
	}

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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</body>

</html>
<script>
$(document).ready(function() {
	// process the form
	$('#formId').submit(function(event) {
		//console.log(event.className);
		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		var formData = {
			'name': $('input[name=inputValue]').val(),
			'type': typeOFOperationForSubmitForm,
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'ConnectOrDisconnectUser.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				// log data to the console so we can see
				//console.log(data);
				//alert(data['msg']);
				var ele = globalForConnectOrDisconnect;
				if(ele.innerHTML == "Connect") {
					ele.innerHTML = "Request Sent";
					ele.disabled = true;
				} else ele.innerHTML = "Connect";
				// here we will handle errors and validation messages
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	$('#formIdGet').submit(function(event) {
		//fnction to get the sent and received connections
		var formData = {
			'nameGet': $('input[name=inputValueGet]').val(),
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'GetSorRconns.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				//console.log(data);
				count = Object.keys(data['msg']).length;
				try {
					if(count == 1)
						if(data['msg'][""][0] == null) count = 0;
				} catch (err) {}
				allUsers = [];
				allUsersPRO = {};
				if(count == 0) {
					if(document.getElementById("inGet").value == "Sent") {
						document.getElementById("InfoRes").innerHTML = "No connections sent";
					} else {
						document.getElementById("InfoRes").innerHTML = "No received connections";
					}
					document.getElementById("navTabContent").innerHTML = "";
				} else {
					if(document.getElementById("inGet").value == "Sent") {
						document.getElementById("InfoRes").innerHTML = "Sent Connections";
					} else {
						document.getElementById("InfoRes").innerHTML = "You have received connections from Extraterrestrial beings";
					}
					document.getElementById("navTabContent").innerHTML = "";
					if(document.getElementById("inGet").value == "Sent") {
						for(var key in data['msg']) {
							//disp(pro,name,username,flagForConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
							disp(true, data['msg'][key][0], data['msg'][key][1], key, false, false, false, true);
						}
					} else {
						for(var key in data['msg']) {
							//disp(pro,name,username,flagForConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
							disp(true, data['msg'][key][0], data['msg'][key][1], key, false, false, true, false);
						}
					}
				}
				//console.log(allUsersPRO);
				//
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	$('#formIdGetALL').submit(function(event) {
		//form for getting all the users which are not in the connections,sent,received
		var formData = {
			'nameGetALL': $('input[name=inputValueGetALL]').val(),
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'GetALLWithoutConnections.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				document.getElementById("InfoRes").innerHTML = "Available Users";
				document.getElementById("navTabContent").innerHTML = "";
				allUsers = [];
				allUsersPRO = {};
				//console.log(data);   
				cont = Object.keys(data['msg']).length;
				for(var key in data['msg']) {
					//function disp(flagForLoadSearchResultsOrLoadDefaultResult,pro,name,username,flagForConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
					disp(true, data['msg'][key][0], data['msg'][key][1], key, true, false, false, false);
				}
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	$('#formIdGetALLConnections').submit(function(event) {
		//form for getting all the users which are in the connections 
		var formData = {
			'nameGetALL': $('input[name=inputValueGetALLConnections]').val(),
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'GetALLConnections.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				document.getElementById("InfoRes").innerHTML = "Your Connections";
				document.getElementById("navTabContent").innerHTML = "";
				allUsers = [];
				allUsersPRO = {};
				//console.log(data);
				cont = Object.keys(data['msg']).length;
				//console.log("all connections lengtht "+cont);             
				for(var key in data['msg']) {
					//disp(pro,name,username,flagForConnectorDisconnect,flagForChat,flagForAcceptOrRejectConnections,flagForSentConnections)
					disp(true, data['msg'][key][0], data['msg'][key][1], key, false, true, false, false);
				}
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	$('#formIdCancelRequest').submit(function(event) {
		//form for cancelling the sent request
		var formData = {
			'nameCancelRequest': $('input[name=inputValueCancelRequest]').val(),
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'CancelRequest.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				//console.log(data);
				globalElementCancelRequest.innerHTML = "Canceled";
				globalElementCancelRequest.disabled = true;
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
	$('#formIdAcceptOrReject').submit(function(event) {
		//form for accepting or rejecting the received request
		var formData = {
			'nameWithAorR': $('input[name=inputValueAcceptorRejectUsername]').val(),
			'result': ResultFromReceivedRequest
		};
		// process the form
		$.ajax({
				type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url: 'AcceptOrReject.php', // the url where we want to POST
				data: formData, // our data object
				dataType: 'json', // what type of data do we expect back from the server
				encode: true
			})
			// using the done promise callback
			.done(function(data) {
				//console.log(data); 
				if(global1AcceptOrRejectRequestForDisable.innerHTML == ResultFromReceivedRequest) {
					if(ResultFromReceivedRequest == "Accept") {
						global1AcceptOrRejectRequestForDisable.innerHTML = "Accepted";
						global1AcceptOrRejectRequestForDisable.disabled = true;
						global2AcceptOrRejectRequestForDisable.disabled = true;
					} else {
						global1AcceptOrRejectRequestForDisable.innerHTML = "Rejected";
						global1AcceptOrRejectRequestForDisable.disabled = true;
						global2AcceptOrRejectRequestForDisable.disabled = true;
					}
				} else {
					if(ResultFromReceivedRequest == "Accept") {
						global2AcceptOrRejectRequestForDisable.innerHTML = "Accepted";
						global1AcceptOrRejectRequestForDisable.disabled = true;
						global2AcceptOrRejectRequestForDisable.disabled = true;
					} else {
						global2AcceptOrRejectRequestForDisable.innerHTML = "Rejected";
						global1AcceptOrRejectRequestForDisable.disabled = true;
						global2AcceptOrRejectRequestForDisable.disabled = true;
					}
				}
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
});
</script>
