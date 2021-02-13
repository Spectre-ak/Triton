<?php
//script to connect or disconnect from a user 
if($_COOKIE['status']=='1'){
  
}
else{
echo("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}
$msg = array();
if (empty($_POST['nameCancelRequest'])){
	$msg['msg']="emptyValue";
}
else{
	$user=$_POST['nameCancelRequest'];
	$ThisUser=$_COOKIE['statusUsername'];
	//remove $user from the sent of $ThisUser
	//remove $ThisUser from the receive of $user


	$json_obf=file_get_contents("networksJSON.json");
	$data=json_decode($json_obf,true);

	$arr=array();
	for($i=0;$i<count($data[$ThisUser]['sentConnections']);$i++){
		if($data[$ThisUser]['sentConnections'][$i]!=$user){
			array_push($arr,$data[$ThisUser]['sentConnections'][$i]);
		}
	}
	$data[$ThisUser]['sentConnections']=$arr;


	$arr=array();
	for($i=0;$i<count($data[$user]['ReceivedConnections']);$i++){
		if($data[$user]['ReceivedConnections'][$i]!=$ThisUser){
			array_push($arr,$data[$user]['ReceivedConnections'][$i]);
		}
	}
	$data[$user]['ReceivedConnections']=$arr;


	$json_object = json_encode($data);
	file_put_contents('networksJSON.json', $json_object);  
	$msg['msg']="Disconnected Successfully";

}

echo json_encode($msg);


 ?>