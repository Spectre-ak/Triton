<?php
if($_COOKIE['status']=='1'){
  
}
else{
echo("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}

$msg = array();

if (empty($_POST['nameGet'])){
	$msg['msg']="emptyValue";
}
else{
	$json_obf=file_get_contents("networksJSON.json");
	$data=json_decode($json_obf,true);
	$ThisUser=$_COOKIE['statusUsername'];

	$serverName = "xxxxxxxxxxxxx";
        $connectionOptions = array("Database" => "xxxxxxxxx", 
            "Uid" => "xxxxxxxxxx", 
            "PWD" => "xxxxxxxxxxx"); 
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));

	try{
		$get="";
		if($_POST['nameGet']=="followers"){
			$get="Followers";
		}
		else{ 
			$get="Following";
		}
		$msg['msg']=$data[$ThisUser][$get];
			$followingPROFILE=array();
			
			for($i=0;$i<count($data[$ThisUser][$get]);$i++){
				$user=$data[$ThisUser][$get][$i];
	
			$tsql= "SELECT profilePhoto,orignalName FROM dbo.allUsersInfo WHERE username='$user'";
	        	$getResults= sqlsrv_query($conn, $tsql);
	        	if(!$getResults){
	        		$msg['msg']="serverError";break;
	        	}
	        	$row = sqlsrv_fetch_array($getResults);
	        	$followingPROFILE[$user]=array($row['profilePhoto'],$row['orignalName']);
			}  
			$msg['msg']=$followingPROFILE;
		

	}
	catch(Exception $e){
		$msg['msg']=$e->getMessage();
	}
    
	
}
echo json_encode($msg);
?>