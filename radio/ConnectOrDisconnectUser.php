<?php
//script to connect or disconnect from a user
if ($_COOKIE['status'] == '1') {
} else {
    echo ("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}
$msg = array();
function SendConnectRequest($user) {
    $ThisUser = $_COOKIE['statusUsername'];
    //add $user to the sent in the json file of $ThisUser
    //add $ThisUser to the received in the json of $user
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    if (in_array($user, $data[$ThisUser]['sentConnections']) == false) {
        array_push($data[$ThisUser]['sentConnections'], $user);
    }
    if (in_array($ThisUser, $data[$user]['ReceivedConnections']) == false) {
        array_push($data[$user]['ReceivedConnections'], $ThisUser);
    }
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $msg['msg'] = "request sent Successfully";
}
function DisconnectUser($user) {
    $ThisUser = $_COOKIE['statusUsername'];
    //remove $user from the connections of the $ThisUser
    //remove $ThisUser from the conenction of $user
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    $arr = array();
    for ($i = 0;$i < count($data[$ThisUser]['Connections']);$i++) {
        if ($data[$ThisUser]['Connections'][$i] != $user) {
            array_push($arr, $data[$ThisUser]['Connections'][$i]);
        }
    }
    $data[$ThisUser]['Connections'] = $arr;
    $arr = array();
    for ($i = 0;$i < count($data[$user]['Connections']);$i++) {
        if ($data[$user]['Connections'][$i] != $ThisUser) {
            array_push($arr, $data[$user]['Connections'][$i]);
        }
    }
    $data[$user]['Connections'] = $arr;
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $msg['msg'] = "Disconnected Successfully";
}
if (empty($_POST['name'])) {
    $msg['msg'] = "emptyValue";
} else {
    //$msg['msg']=$_POST['name'];
    if ($_POST['type'] == "Connect") SendConnectRequest($_POST['name']);
    else DisconnectUser($_POST['name']);
}
echo json_encode($msg);
?>
