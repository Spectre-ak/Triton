<?php

$usernameGlobal="";


if(isset($_POST['logout'])){
    setcookie('status',"",time() + (1),'/','sqltry3.azurewebsites.net');
    setcookie('statusUsername',"",time() + (1),'/','sqltry3.azurewebsites.net');
   // setcookie('statusUserTable',"",time() + (1),'/','sqltry3.azurewebsites.net');
        
    echo("
        <script>
        
        </script>");
  }


  if($_COOKIE['status']=='1'){
      echo("
          <script>
          window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';
          </script>");
    }
    else{

    }

  function displaySignup(){
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $passwordRepeat=$_POST['password-repeat'];
    $name=$_POST['name'];
    if($password!=$passwordRepeat){
      echo "password mismatch try again";exit();
    }
   
 
    $serverName = "xxxxxxxxxxxxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxxxxxx", 
        "Uid" => "xxxxxxxxxxxxxxxxx", 
        "PWD" => "xxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));
    $tsql= "SELECT * FROM dbo.allUsersInfo";
    $getResults= sqlsrv_query($conn, $tsql);

    if ($getResults == FALSE){
       echo ("<script>alert('Some Error occured');</script>");
       return "exit";
    }
    //checking if the credentials are valid or not
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
       if($row['username']==$username){
            return "Username taken";
       }
       else if($row['email']==$email){
            return "Email is in use";
       }
    }
    //main database enrty
    $tsql= "INSERT INTO dbo.allUsersInfo (username,orignalName,email,passkey,tableName,followers,followings,likedPosts,totalComments,userPosts,profilePhoto,coverPhoto,location)
            VALUES ('$username', '$name', '$email','$password','-','0','0','0','0','0','css/images/profile_picture.png','css/images/cover.png','Blue Planet')";
    $getResults= sqlsrv_query($conn, $tsql);
    if ($getResults == FALSE){
       echo ("<script>alert('Some Error occured');</script>");
       return "exit";
    }

    
    $arrSubs=array("Followers"=>array(),"Following"=>array(),"Connections"=>array(),"ReceivedConnections"=>array(),"sentConnections"=>array(),"LikedPosts"=>array(),"UserPosts"=>array());
    $json_obf=file_get_contents("networksJSON.json");
    $data=json_decode($json_obf,true);
    //$arr=array($username=>$arrSubs);
    //array_push($data,$arr);
    $data[$username]=$arrSubs;
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);  

    
    $usernameGlobal=$username;
    setcookie('statusUsername',$username,time() + (86400 * 30),'/','sqltry3.azurewebsites.net');
    
    return "OK";
    }
    
    
    
    
    
  function displaylogiin(){ 
      $username=$_POST['usernameLogin'];
      $password=$_POST['passwordLogin'];

      //echo "<script>alert('$username'+' '+' $password');</script>";

      $serverName = "xxxxxxxxxxx";
      $connectionOptions = array("Database" => "xxxxxxxxxx", 
          "Uid" => "xxxxxxxxx", 
          "PWD" => "xxxxxxxxxxxx");
      $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));
      $tsql= "SELECT * FROM dbo.allUsersInfo";
      $getResults= sqlsrv_query($conn, $tsql);
      if ($getResults == FALSE){
        print_r(sqlsrv_errors());
        echo "errororo";
         echo ("<script>alert('Some Error occured');</script>");
         return "exit";
      }
      //checking if the credentials are valid or not for user login
      while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
         if(($row['username']==$username || $row['email']==$username) && $row['passkey']==$password){
              $usernameGlobal=$row['username'];
              setcookie('statusUsername',$row['username'],time() + (86400 * 30),'/','sqltry3.azurewebsites.net');
              return "userValid";
         }
      }
//echo "errororo";
      return "userInvalid";
  }

  if(isset($_POST['signup'])){
    $res=displaySignup();
    if($res=="exit"){exit();}
    else if($res=="Username taken"){
        echo("
          <script>
          window.onload=function(){
            document.getElementById('signupButton').click();
            document.getElementById('error').innerHTML='Username taken';
          }
          
          </script>");
    }
    else if($res=="Email is in use"){
      echo("
          <script>
          window.onload=function(){
            document.getElementById('signupButton').click();
            document.getElementById('error').innerHTML='Email is in use';
          }
          
          </script>");
    }
    else{
      echo("
          <script>
          window.onload=function(){
            document.getElementById('signupButton').click();
            document.getElementById('error').innerHTML='Acc created sucessfully';
          }
          </script>");

    //cookie works-- and redirect for the demno page

      //setcookie('status','1');
      setcookie('status',"1",time() + (86400 * 30),'/','sqltry3.azurewebsites.net');
      
      
      echo("
          <script>
          window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';
          </script>");
    }

  }

  if(isset($_POST['login'])){
    $resFromLogin=displaylogiin();
   // echo "<script>alert('$resFromLogin');</script>";
    if($resFromLogin=="userValid"){
      setcookie('status',"1",time() + (86400 * 30),'/','sqltry3.azurewebsites.net');
      //setcookie('statusUsername',$usernameGlobal,time() + (86400 * 30),'/','sqltry3.azurewebsites.net');

      echo("
          <script>
          window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';
          </script>");
    }
    else{
      echo("
          <script>
          window.onload=function(){
          
            document.getElementById('loginErrorMessage').style.display='block';
          }
         
          </script>");
    }
  }


?>


<!DOCTYPE html>
<html>
    <head>
      <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        
        <title>Login</title>
        <link rel="stylesheet" href="css/login.css">
        <link rel="shortcut icon" href="css/images/logo.png" />
        
    </head>

    <body> 
    <div class="conta2iner" style="padding-left:10px">
     <h4><i>Welcome to Triton.</i></h4> 
     <h5><i>A social media network for space enthusiast.</i></h5> 
     <h5><i>Go ahead create an account or login.</i></h5>
     <i ><a style="color:white;" href="https://sqltry3.azurewebsites.net/uploads/loginsignup/About.php">About Us.</a></i>    
       
    </div>    

<script>
 // alert("Asda");



function checkAgain(){
    console.log("ASdasd");
    var password=document.getElementById('password').value;
    var password_repeat=document.getElementById('password-repeat').value;
    var name=document.getElementById('nameSignupID').value;
    var username=document.getElementById('username').value;
    if(password!=password_repeat){
        document.getElementById('error').innerHTML="Password do not match";
        document.getElementById('error').style.color="red";
        document.getElementById('error').style.fontStyle="normal";return;
    }
    if(password.length<5){
       //alert("len")
        document.getElementById('error').innerHTML="Password must contain atleast one capital, small, numeric value and minimum of 5 characters";
        document.getElementById('error').style.color="red";
        document.getElementById('error').style.fontStyle="normal"; return;
    }
    if(password.search(/[a-z]/)<0){
        //alert("small")
        document.getElementById('error').innerHTML="Password must contain atleast one capital, small, numeric value and minimum of 5 characters";
        document.getElementById('error').style.color="red";
        document.getElementById('error').style.fontStyle="normal"; return;
    }
    if(password.search(/[A-Z]/)<0){
//alert("capitakl")
        document.getElementById('error').innerHTML="Password must contain atleast one capital, small and numeric value";
        document.getElementById('error').style.color="red"; 
        document.getElementById('error').style.fontStyle="normal"; return;
    }
    if(password.search(/[0-9]/)<0){
       // alert("no")
        document.getElementById('error').innerHTML="Password must contain atleast one capital, small and numeric value";
        document.getElementById('error').style.color="red";
        document.getElementById('error').style.fontStyle="normal"; return;
    }
    if(username=="" || username.includes(".") || username.includes("#") ||username.includes("$") ||username.includes("[") || username.includes("]") || username.indexOf('\'') >= 0 || username.indexOf('"') >= 0 ){
      //, "#", "$", "[", or "]"  
      document.getElementById('error').innerHTML="Username must not contain . , # , $ , [ , ] , single & double quotes ";
      document.getElementById('error').style.color="red";
      document.getElementById('error').style.fontStyle="normal"; return;
     }
     if(name.indexOf('\'') >= 0 || name.indexOf('"') >= 0 ){
       document.getElementById('error').innerHTML="Name must not contain single & double quotes ";
       document.getElementById('error').style.color="red";
       document.getElementById('error').style.fontStyle="normal"; return;
     }
     
    document.getElementById('error').innerHTML="Password must contain atleast one capital, small and numeric value";
        document.getElementById('error').style.color="lightgreen";
        document.getElementById('error').style.fontStyle="italic";
       // alert("done")
     
     
    document.getElementById('finalSubmit').click();
}
</script>

<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" method="POST" action="<?php $_PHP_SELF ?>" >
    <div class="container">
      <h1 style="text-align: center;">Sign Up</h1>
      <br>
      <input type="email" placeholder="Enter Email" name="email" class="s_input" required>

      <input type="text" placeholder="Enter Username" name="username" id="username" class="s_input" required>

      <input type="text" placeholder="Enter Name" name="name" id="nameSignupID" class="s_input" required>

      <input type="password" placeholder="Enter Password" on name="password" class="s_input" id="password" pattern=^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$ required>

      <input type="password" placeholder="Repeat Password" name="password-repeat" class="s_input" id="password-repeat" pattern=^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$ required> 

      <p id='error'><i>Password must contain atleast one capital, small, numeric value and minimum of 5 characters</i></p>
       
      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="signup" class="cancelbtn">Cancel</button>
        <button type="button" class="signup" style="margin:10px;" onclick="checkAgain();">Sign Up</button>
        <button type="submit" name="signup" id="finalSubmit" value="signup" style="display: none"></button>
      </div>
    </div>
  </form>
</div>

  

  
     <div class="left" id="border">
      
      <div class="h1" >
          <img class="logo" src="css/images/cover.png">
          
      </div>

    </div>


    <div class="right">
        <a style="display: none;color: red" id="loginErrorMessage">Username or password invalid</a>
        <form method="POST" action="<?php $_PHP_SELF ?>">
            <p><input class="input" type="text" name="usernameLogin" placeholder="Username or Email Address" required ></p>
            
            <p><input class="input" type="password" name="passwordLogin" placeholder="Password" required ></p>
            
            <p><a href="ForgotPassword.php" id="forgetPass">Forgot Password</a></p>
            <input type="submit" id="login" name="login" value="Log In">
            </form>

            <p style="padding-top: 0.40%;">OR</p>
            
            

            <button class="signup" id="signupButton" onclick="document.getElementById('id01').style.display='block'" >Sign Up</button>
        
    </div>
    
 
     

  
  
    
    
    

      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>

