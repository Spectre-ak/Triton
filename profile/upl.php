<?php
if($_COOKIE['status']=='1'){
        echo ("<script>alert('debug echo');</script>");
        $username=$_COOKIE['statusUsername'];
        
            
      }
      
      else{
        echo("
            <script>
            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
            </script>");
      }
	   
	  ?>
      
<?php



if(isset($_POST['image'])){
	
    $username=$_COOKIE['statusUsername'];
    $serverName = "xxxxxxxxxxx";
  $connectionOptions = array("Database" => "xxxxxxxxxxxx", 
    "Uid" => "xxxxxxxxxxxxx", 
    "PWD" => "xxxxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));
    
    $tsql= "SELECT * FROM dbo.allUsersInfo";
    $getResults= sqlsrv_query($conn, $tsql);
    
    if ($getResults == FALSE){
        echo ("<script>alert('Some Error occured while profile loading');</script>");
        exit();
    }
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
         if($row['username']==$username || $row['email']==$username){
               if($row['profilePhoto']!='css/images/profile_picture.png'){
                 $imgOld=$row['profilePhoto'];
                 echo ("<script>alert('$imgOld');</script>");
                  unlink($imgOld); 
                }
               break;
         }
      }
          
          
    $data = $_POST['image'];


	$image_array_1 = explode(";", $data);

	
	$image_array_2 = explode(",", $image_array_1[1]);

	
	$data = base64_decode($image_array_2[1]);

	$image_name = 'uploads/pro' . $username . '.png';

		  
	file_put_contents($image_name, $data);
    
    $jpgName='uploads/pro' . $username . '.jpg';

	$img=imagecreatefrompng($image_name);
	imagejpeg($img,$jpgName,70);
	//imagedestory($img);
	echo $image_name;
    
	unlink($image_name);
    
    $sql = "UPDATE dbo.allUsersInfo SET profilePhoto='$jpgName' WHERE username='$username' ";
    $getResults= sqlsrv_query($conn, $sql);
    if ($getResults == FALSE){
       echo ("<script>alert('Some Error occured while updating table name');</script>");
       return "exit";
    }
    
   echo("
        <script>
        window.reload();
        alert('$jpgName');
        document.getElementById('uploaded_image').src='$jpgName';
          window.onload=function(){
           document.getElementById('uploaded_image').src='$jpgName';
           }
        </script>");
}

?>