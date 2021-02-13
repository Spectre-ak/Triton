<?php
//script to get all users which are in connections
if($_COOKIE['status']=='1'){
  
}
else{
echo("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}
$msg = array();

if (empty($_POST['nameGetALL'])){
	$msg['msg']="emptyValue";
}
else{
	$json_obf=file_get_contents("networksJSON.json");
	$data=json_decode($json_obf,true);
	$ThisUser=$_COOKIE['statusUsername'];
	$username=$_COOKIE['statusUsername'];
	
	$serverName = "xxxxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxxxx", 
        "Uid" => "xxxxxxxxxxxxxx", 
        "PWD" => "xxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die( print_r( sqlsrv_errors(), true));

	try{
		$usersPROFILE=array();
		
		$tsql= "SELECT * FROM dbo.allUsersInfo";
        $getResults= sqlsrv_query($conn, $tsql);

        if ($getResults == FALSE){
           $msg['msg']="error";
           exit();
        }
        else{
        	while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
	           if($row['username']==$username || $row['email']==$username){
	                continue;
	           }
	           if(in_array($row['username'],$data[$username]['Connections'])){
	                 $usersPROFILE[$row['username']]=array($row['profilePhoto'],$row['orignalName']);
	           }
	            
	        } 
        }

		$msg['msg']=$usersPROFILE;
	
	}
	catch(Exception $e){
		$msg['msg']=$e->getMessage();
	}
  
}
echo json_encode($msg);
?>