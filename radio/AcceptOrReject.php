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
function AcceptUserRequest($user) {
    $ThisUser = $_COOKIE['statusUsername'];
    //add $user to the connection in the json file of $ThisUser
    //remove $user form the receive of $thisUser
    //add $ThisUser to the connection in the json of $user
    //remove $ThisUser from the sent of $user
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    //add $user to the connection in the json file of $ThisUser
    if (in_array($user, $data[$ThisUser]['Connections']) == false) {
        array_push($data[$ThisUser]['Connections'], $user);
    }
    //add $ThisUser to the connection in the json of $user
    if (in_array($ThisUser, $data[$user]['Connections']) == false) {
        array_push($data[$user]['Connections'], $ThisUser);
    }
    //remove $user form the receive of $thisUser
    $arr = array();
    for ($i = 0;$i < count($data[$ThisUser]['ReceivedConnections']);$i++) {
        if ($data[$ThisUser]['ReceivedConnections'][$i] != $user) {
            array_push($arr, $data[$ThisUser]['ReceivedConnections'][$i]);
        }
    }
    $data[$ThisUser]['ReceivedConnections'] = $arr;
    //remove $ThisUser from the sent of $user
    $arr = array();
    for ($i = 0;$i < count($data[$user]['sentConnections']);$i++) {
        if ($data[$user]['sentConnections'][$i] != $ThisUser) {
            array_push($arr, $data[$user]['sentConnections'][$i]);
        }
    }
    $data[$user]['sentConnections'] = $arr;
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $msg['msg'] = "request accepted Successfully";
}
function RejectUserRequest($user) {
    $ThisUser = $_COOKIE['statusUsername'];
    //remove $user from the receive of the $ThisUser
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    $arr = array();
    for ($i = 0;$i < count($data[$ThisUser]['ReceivedConnections']);$i++) {
        if ($data[$ThisUser]['ReceivedConnections'][$i] != $user) {
            array_push($arr, $data[$ThisUser]['ReceivedConnections'][$i]);
        }
    }
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $msg['msg'] = "Rejected Successfully";
}
if (empty($_POST['nameWithAorR'])) {
    $msg['msg'] = "emptyValue";
} else {
    //$msg['msg']=$_POST['name'];
    if ($_POST['result'] == "Accept") AcceptUserRequest($_POST['nameWithAorR']);
    else RejectUserRequest($_POST['nameWithAorR']);
}
echo json_encode($msg);
?>
