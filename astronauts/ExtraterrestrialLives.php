<link rel="shortcut icon" href="css/images/logo.png" />
    

  
<form action="followUser.php" method="POST" style="display:none;" id="formId">
  <input type="text" id="in" name="inputValue" value="xxxxxxxxxxx"/>
</form>

<form action="GetFollowers.php" method="POST" style="display:none;" id="formIdGet">
  <input type="text" id="inGet" name="inputValueGet" value="xxxxxxxxxxx"/>
</form>

<form action="GetALL.php" method="POST" style="display:none;" id="formIdGetALL">
  <input type="text" id="inGetALL" name="inputValueGetALL" value="xxxxxxxxxxx"/>
</form>

<script>
      var allUsersPRO={};
        var allUsers=[];
          function disp(pro,name,username,flag){
           // console.log(pro+" "+name+" "+username);
            var p2=document.createElement('p');
            var img=document.createElement('IMG');     
            allUsers.push(username); 
            allUsersPRO[username]=[pro,name];
            img.setAttribute('src',pro);
            img.setAttribute('width','40px');
            img.setAttribute('height','40px');
            img.setAttribute('style','margin-right:40px');
            img.setAttribute('style','border-radius:40px 40px 40px 40px');
            
            
            img.setAttribute('alt','Avatar');
            
            var ele=document.createElement('button');
            ele.id="buttonID"
            ele.style.float='right';
            if(flag)
              ele.innerHTML='Follow';
            else 
              ele.innerHTML='Unfollow';
            ele.className='btn btn-primary'
            ele.onclick=function(){submitForm(username,this);};
             
            p2.appendChild(img);
            
            var a=document.createElement('a');
            a.style.paddingLeft='6px';
            a.innerHTML=name;
            p2.appendChild(a);
            
           // if(flag)
              p2.appendChild(ele);
            
            document.getElementById('navTabContent').appendChild(p2);
          }
          
          function dispONLY(pro,name,username,flag){
            //function to load only the results and  not append
           
           // console.log(pro+" "+name+" "+username);
            var p2=document.createElement('p');
            var img=document.createElement('IMG');     
           
            img.setAttribute('src',pro);
            img.setAttribute('width','40px');
            img.setAttribute('height','40px');
            img.setAttribute('style','margin-right:40px');
            img.setAttribute('style','border-radius:40px 40px 40px 40px');
            
            
            img.setAttribute('alt','Avatar');
            
            var ele=document.createElement('button');
            ele.id="buttonID"
            ele.style.float='right';
            if(flag)
              ele.innerHTML='Follow';
            else 
              ele.innerHTML='Unfollow';
            ele.className='btn btn-primary'
            ele.onclick=function(){submitForm(username,this);};
             
            p2.appendChild(img);
            
            var a=document.createElement('a');
            a.style.paddingLeft='6px';
            a.innerHTML=name;
            p2.appendChild(a);
            
           // if(flag)
            p2.appendChild(ele);
            
            document.getElementById('navTabContent').appendChild(p2);
          }
          
          
          
          //to be used later for sorting based on the search result for the Followers and Following
          var allUsersProFWFI={};
                
            </script>
            
        <script> 
        
    function hideSearch(){
      document.getElementById("searchdiv").style.display="none";
      document.getElementById("serchId").style.display="block";
      
      //by exiting the search bar loading the allUsersPRO list as default
      document.getElementById("navTabContent").innerHTML="";
      var flag=false;//boolean value for cheking weather the OtherPeople/Followers or Following is loaded.
      if(document.getElementById("11").className=="nav-link active" || document.getElementById("22").className=="nav-link active")flag=true;
      for(var key in allUsersPRO){
          dispONLY(allUsersPRO[key][0],allUsersPRO[key][1],key,flag);
      }
      document.getElementById("InfoRes").innerHTML="Available Users.";
        
    }
    function hideOrShowSearchBar(){
      if(document.getElementById("searchdiv").style.display=="none"){
        document.getElementById("serchId").style.display="none";
        document.getElementById("searchdiv").style.display="block";
      }
    }
    function LoadFollowers(){
      document.getElementById("inGet").value="followers";
      //alert("foolowers");
      $("#formIdGet").submit();
    }
    function LoadFollowings(){
      document.getElementById("inGet").value="following";
      //alert("foolowing");
      $("#formIdGet").submit();
    }
     
    
    function LoadDefault(){
      document.getElementById("inGetALL").value="ALL";
      //alert("ALL");
      $("#formIdGetALL").submit();
    }
  
     var typeOFOperationForSubmitForm="";
     var global;
     function submitForm(username,ele){
        //alert("ads");
        document.getElementById("in").value=username;
        typeOFOperationForSubmitForm=ele.innerHTML;
        $("#formId").submit();
        global=ele;
        //console.log(ele);
        //console.log(ele.className);
        //ele.className="btn btn-primary disabled";
        
     }
   
       
    </script>
<?php
if($_COOKIE['status']=='1'){
  
      
        
        
  
        //echo ("<script>alert('debug echo');</script>");
        $username=$_COOKIE['statusUsername'];
        $serverName = "xxxxxxxxxxxxx";
        $connectionOptions = array("Database" => "xxxxxxxxx", 
            "Uid" => "xxxxxxxxxx", 
            "PWD" => "xxxxxxxxxxx"); 
        $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));

        $tsql= "SELECT * FROM dbo.allUsersInfo";
        $getResults= sqlsrv_query($conn, $tsql);

        if ($getResults == FALSE){
           echo ("<script>alert('Some Error occured while profile loading');</script>");
           exit();
        }
       
        $json_obf=file_get_contents("networksJSON.json");
        $data=json_decode($json_obf,true);





        $name=array();
        $userName=array();
        $location=array();
        $profile_picture_link=array();
        //$cover_photo_link="";


        


        
        while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
           if($row['username']==$username || $row['email']==$username){
                continue;
           }
           if(in_array($row['username'],$data[$username]['Followers']) || in_array($row['username'], $data[$username]['Following'])){
                continue;
           }
           array_push($name,$row['orignalName']);
           array_push($location,$row['location']);
           array_push($profile_picture_link,$row['profilePhoto']);
           array_push($userName,$row['username']);   
        } 
        
        echo("
            ");
        
        
        
        echo("
            <script>
              window.onload=function(){
               
              ");
              
              for ($i=0; $i<count($name) ; $i++) { 
                $pro=$profile_picture_link[$i];
                $nam=$name[$i]; 
                $usNam=$userName[$i];
             //     echo("
              //     disp('$pro','$nam','$usNam',true);
              //     ");
                   
                   echo("disp(");
                		echo(" \"$pro\" "); echo(',');
                		echo(" \"$nam\" "); echo(',');
                		echo(" \"$usNam\" "); echo(',');
                		echo("true");   
                		 
                		
                		echo(");");
    
    
    
              }
              
          echo("}
            
             
            </script>");
     }
      
      else{
        echo("
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
    
   
    
    <title>Astronauts</title>
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
            background-color: green!important; 
            color: green; 
        }
</style>
<div class="container" style="padding-top: 4px;padding-bottom: 10px; text-align: center;" >
  <img id="logo" src="css/images/cover.png" width="200px" style="border-radius: 40px;">
</div>

<div class="container" >
	<ul class="nav nav-tabs nav-justified">
        <li class="nav-item" style="background-color: #131316;">
          <a class="nav-link" href="#peter" 
           role="tab" data-toggle="tab" id="1" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/SpaceShuttel.php';"><i class="fa fa-rocket" style="color: #0c82e2"></i> Space Shuttel</a>
        </li>
        <li class="nav-item" style="background-color: #131316">
          <a class="nav-link active" href="#danny" role="tab"
            data-toggle="tab" id="2" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/ExtraterrestrialLives.php';"><i class="fa fa-users" aria-hidden="true"></i> Astronauts</a>
        </li>
        <li class="nav-item" style="background-color: #131316" >
          <a class="nav-link" href="#alberto" role="tab"
            data-toggle="tab" id="4" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/Radio.php';"><i class="fa fa-comments" aria-hidden="true"></i> Radio</a>
        </li>
        <li class="nav-item" style="background-color: #131316" >
          <a class="nav-link" href="#agumbe"role="tab" 
            data-toggle="tab" id="3" onclick="window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/profile.php';"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
        </li>
    </ul>
</div>

<br>
<style>

</style>  
<div class="container">
  <div class="container" id="navTabForOptions">
    <div class="container" >
      <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
          <a class="nav-link active" onclick="LoadDefault();" data-toggle="tab" id="11" href="#"><i class="fa fa-user" aria-hidden="true" style="color: #0c82e2"></i> All Users</a>
        </li>
        <li onclick="LoadFollowers();" class="nav-item">
          <a class="nav-link" data-toggle="tab" id="22" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> Followers</a>
        </li>
         <li onclick="LoadFollowings();" class="nav-item" >
          <a class="nav-link" data-toggle="tab" id="33" href="#"><i class="fa fa-users" aria-hidden="true" style="color: #0c82e2"></i> Following</a>
        </li>        
      </ul>
    </div>
    <div style="padding-left: 10px;padding-top: 2px;">
      <button onclick="hideOrShowSearchBar();" class="btn btn-secondary"  id="serchId" style="background-color:  #131316;">
        <i class="fa fa-search" aria-hidden="true" style="color: #0c82e2"></i> 
      </button> 
    </div>
    
    <div style="display: table; margin-left:auto;text-align: left; display: none" id="searchdiv">
      <div class="input-group"  style="padding: 4px;" >
          <input class="form-control border-secondary py-2" id="searchID" type="search" placeholder="Type name..." style="border-radius:40px;">
              <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" style="background-color: white;border-radius: 20px" onclick="hideSearch();">
                      <i class="fa fa-times" aria-hidden="true" style="color: #0c82e2"></i>
                  </button>
              </div>
      </div>
    </div>

    <p style="padding: 4px;padding-left: 10px" id="InfoRes"><big>Available Users</big></p>
    
    <div  id="navTabContent">
      
      <!--<p> <img src="./uploads/default.png" alt="Avatar" height="40" width="40" style="border-radius:40px 40px 40px 40px"> Admin</p>
      -->
      </div>
    
  </div>
  
  
 
   
</div>





    

 <style>
  3:active{
    background-color: red;
  }


 </style>
 
 
 
<script>
  
    document.getElementById("searchID").addEventListener('input',function(e){
      console.log(document.getElementById("11").className);
      console.log(document.getElementById("22").className);
      console.log(document.getElementById("33").className);
      document.getElementById("navTabContent").innerHTML="";
      var val=document.getElementById("searchID").value;
      
      var flag=false;//boolean value for cheking weather the OtherPeople/Followers or Following is loaded.
      if(document.getElementById("11").className=="nav-link active" || document.getElementById("22").className=="nav-link active")flag=true;
        
      if(val==""){
        //load the whole list
        //allUsersPRO
        for(var key in allUsersPRO){
          dispONLY(allUsersPRO[key][0],allUsersPRO[key][1],key,flag);
        }
        document.getElementById("InfoRes").innerHTML="Available Users.";
      }
      else{
          val=val.toLowerCase();
          for(var key in allUsersPRO){
            var name=allUsersPRO[key][1];
            name=name.toLowerCase();
            if(val.length>name.length)continue;
            if(name.substring(0,val.length)==val){
              dispONLY(allUsersPRO[key][0],allUsersPRO[key][1],key,flag);
            }
          }
          if(document.getElementById("navTabContent").innerHTML==""){
            document.getElementById("InfoRes").innerHTML="No results found.";
          }
          else{
            document.getElementById("InfoRes").innerHTML="Available Users.";
          }
      }
    
    });                 
                    
                    
// Defining event listener function
function displayWindowSize(){
    // Get width and height of the window excluding scrollbars
    var w = window.innerWidth;
    var h = window.innerHeight;
    
    // Display result inside a div element
   ///document.getElementById("result").innerHTML = "Width: " + w + ", " + "Height: " + h;
    //alert(w+" "+h);
  //  alert(+" "+);
   document.getElementById("navTabForOptions").style.height=h-59+"px";
   //document.getElementById("mainContentDiv").style.maxHeight=h+"";
   if(w<446){
      document.getElementById("11").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i>";
    document.getElementById("22").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i>";
    document.getElementById("33").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i>";
  }
  else{
    document.getElementById("11").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i> All Users";
    document.getElementById("22").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i> Followers";
    document.getElementById("33").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i> Following";
  }

  if(w<765){
    document.getElementById("1").innerHTML="<i class='fa fa-rocket' style='color: #0c82e2'></i>";
  document.getElementById("2").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i>";
  document.getElementById("3").innerHTML="<i class='fa fa-user' style='color: #0c82e2'></i>";
  document.getElementById("4").innerHTML="<i class='fa fa-comments' style='color: #0c82e2'></i>";
  document.getElementById("logo").style.width="100px";
  
  }
  else{
    document.getElementById("1").innerHTML="<i class='fa fa-rocket' style='color: #0c82e2'></i> Space Shuttel";
  document.getElementById("2").innerHTML="<i class='fa fa-users' style='color: #0c82e2'></i> Astronauts";
  document.getElementById("3").innerHTML="<i class='fa fa-user' style='color: #0c82e2'></i> Profile";
  document.getElementById("4").innerHTML="<i class='fa fa-comments' style='color: #0c82e2'></i> Radio";
  document.getElementById("logo").style.width="200px";
  }
  
}
    
// Attaching the event listener function to window's resize event
window.addEventListener("resize", displayWindowSize);

// Calling the function for the first time
displayWindowSize();
</script>
            

  
 
 

<style>
  #navTabForOptions{
    color: white;
    width: 100%;
    height: 10000px;
    
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
  background: #0b0b0c00;; 
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
                  'name'              : $('input[name=inputValue]').val(),
                  'type'              : typeOFOperationForSubmitForm,
              };

              // process the form
              $.ajax({
                  type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                  url         : 'followUser.php', // the url where we want to POST
                  data        : formData, // our data object
                  dataType    : 'json', // what type of data do we expect back from the server
                              encode          : true
              })
                  // using the done promise callback
                  .done(function(data) {

                      // log data to the console so we can see
                      //console.log(data);

                      //alert(data['msg']);
                      
                      var ele=global;
                    if(ele.innerHTML=="Follow"){
                      ele.innerHTML="Following";
                    }
                    else ele.innerHTML="Unfollowed";
                    ele.disabled=true;
                    
        
                      // here we will handle errors and validation messages
                  });

              // stop the form from submitting the normal way and refreshing the page
              event.preventDefault();
          });

          $('#formIdGet').submit(function(event) {

              // get the form data
              // there are many ways to get this data using jQuery (you can use the class or id also)
              var formData = {
                  'nameGet'              : $('input[name=inputValueGet]').val(),
              };

              // process the form
              $.ajax({
                  type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                  url         : 'GetFollowers.php', // the url where we want to POST
                  data        : formData, // our data object
                  dataType    : 'json', // what type of data do we expect back from the server
                              encode          : true
              })
                  // using the done promise callback
                  .done(function(data) {
                      //console.log(data);
                      count=Object.keys(data['msg']).length;
                      try {
                        if(count==1)
                         if(data['msg'][""][0]==null)count=0;
                      }
                      catch(err) {
                        
                      }
                      allUsers=[];
                      allUsersPRO={};
                      if(count==0){
                        if(document.getElementById("inGet").value=="followers"){
                          document.getElementById("InfoRes").innerHTML="You don't have any Followers.";
                        }
                        else{
                          document.getElementById("InfoRes").innerHTML="You are not following anyone yet.";
                        }
                        document.getElementById("navTabContent").innerHTML="";
                      }
                      else{
                        if(document.getElementById("inGet").value=="followers"){
                          document.getElementById("InfoRes").innerHTML="Extraterrestrial beings following you";
                        }
                        else{
                          document.getElementById("InfoRes").innerHTML="You are following "+count+" Extraterrestrial beings";
                        }
                        document.getElementById("navTabContent").innerHTML=""; 
                        if(document.getElementById("inGet").value=="followers"){
                          for(var key in data['msg']){
                            //disp(pro,name,username);
                            disp(data['msg'][key][0],data['msg'][key][1],key,true);
                          }
                        }
                        else{
                         for(var key in data['msg']){
                            //disp(pro,name,username);
                            disp(data['msg'][key][0],data['msg'][key][1],key,false);
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

              // get the form data
              // there are many ways to get this data using jQuery (you can use the class or id also)
              var formData = {
                  'nameGetALL'              : $('input[name=inputValueGetALL]').val(),
              };

              // process the form
              $.ajax({
                  type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                  url         : 'GetALL.php', // the url where we want to POST
                  data        : formData, // our data object
                  dataType    : 'json', // what type of data do we expect back from the server
                              encode          : true
              })
                  // using the done promise callback
                  .done(function(data) {
                    document.getElementById("InfoRes").innerHTML="Available Users";
                    document.getElementById("navTabContent").innerHTML="";
                     allUsers=[];
                      allUsersPRO={};
                      //console.log(data);
                      cont=Object.keys(data['msg']).length;    
                      for(var key in data['msg']){
                        //disp(pro,name,username);
                        disp(data['msg'][key][0],data['msg'][key][1],key,true);
                      }
                  });

              // stop the form from submitting the normal way and refreshing the page
              event.preventDefault();
          });
          

      });
      
      
</script>
