<?php
//script to get the sent and received connections
if ($_COOKIE['status'] == '1') {
} else {
    echo ("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}
$msg = array();
if (empty($_POST['nameGet'])) {
    $msg['msg'] = "emptyValue";
} else {
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    $ThisUser = $_COOKIE['statusUsername'];
    $serverName = "xxxxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxxxxxxx", "Uid" => "xxxxxxxxxxxxxx", "PWD" => "xxxxxxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    try {
        $get = "";
        if ($_POST['nameGet'] == "Sent") {
            $get = "sentConnections";
        } else {
            $get = "ReceivedConnections";
        }
        //$msg['msg']=$data[$ThisUser][$get];
        $usersPROFILE = array();
        for ($i = 0;$i < count($data[$ThisUser][$get]);$i++) {
            $user = $data[$ThisUser][$get][$i];
            $tsql = "SELECT profilePhoto,orignalName FROM dbo.allUsersInfo WHERE username='$user'";
            $getResults = sqlsrv_query($conn, $tsql);
            if (!$getResults) {
                $msg['msg'] = "serverError";
                break;
            }
            $row = sqlsrv_fetch_array($getResults);
            $usersPROFILE[$user] = array($row['profilePhoto'], $row['orignalName']);
        }
        $msg['msg'] = $usersPROFILE;
    }
    catch(Exception $e) {
        $msg['msg'] = $e->getMessage();
    }
}
echo json_encode($msg);
?>
