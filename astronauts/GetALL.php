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

if (empty($_POST['nameGetALL'])){
	$msg['msg']="emptyValue";
}
else{
	try{
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
        $allUsersPRO=array();
        
        while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
           if($row['username']==$username || $row['email']==$username){
                continue;
           }
           if(in_array($row['username'],$data[$username]['Followers']) || in_array($row['username'], $data[$username]['Following'])){
                continue;
           }
           $allUsersPRO[$row['username']]=array($row['profilePhoto'],$row['orignalName']);
        }
        $msg['msg']=$allUsersPRO; 
	}
	catch(Exception $e){
		$msg['msg']=$e->getMessage();
	}
}
echo json_encode($msg);
?>