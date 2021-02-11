<?php
if ($_COOKIE['status'] == '1') {
} else {
    echo ("
    <script>
    window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
    </script>");
}
$msg = array();
function addUserToJson($userToFollow) {
    $ThisUser = $_COOKIE['statusUsername'];
    //add userToFollow in the follwing array of ThisUser
    //add ThisUser in the followers array of userToFollow
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    if (in_array($userToFollow, $data[$ThisUser]['Following']) == false) {
        array_push($data[$ThisUser]['Following'], $userToFollow);
    }
    if (in_array($ThisUser, $data[$userToFollow]['Followers']) == false) {
        array_push($data[$userToFollow]['Followers'], $ThisUser);
    }
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $serverName = "xxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxx", "Uid" => "xxxxxxxxxx", "PWD" => "xxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    //database update for followings for $ThisUSer
    $tsql = "SELECT followings FROM dbo.allUsersInfo WHERE username='$ThisUser'";
    $getResults = sqlsrv_query($conn, $tsql);
    if (!$getResults) {
        $msg['msg'] = "serverError";
        exit();
    }
    $row = sqlsrv_fetch_array($getResults);
    $followings = $row['followings'] + 1;
    $sql = "UPDATE dbo.allUsersInfo SET followings='$followings' WHERE username='$ThisUser' ";
    $getResults = sqlsrv_query($conn, $sql);
    if ($getResults == FALSE) {
        $msg['msg'] = "serverError2";
        echo $msg;
        exit();
    }
    //databse update for followers of $userToFollow
    $tsql = "SELECT followers FROM dbo.allUsersInfo WHERE username='$userToFollow'";
    $getResults = sqlsrv_query($conn, $tsql);
    if (!$getResults) {
        $msg['msg'] = "serverError";
        exit();
    }
    $row = sqlsrv_fetch_array($getResults);
    $followers = $row['followers'] + 1;
    $sql = "UPDATE dbo.allUsersInfo SET followers='$followers' WHERE username='$userToFollow' ";
    $getResults = sqlsrv_query($conn, $sql);
    if ($getResults == FALSE) {
        $msg['msg'] = "serverError2";
        echo $msg;
        exit();
    }
}
function unfollowUserAndRemoveFromJson($user) {
    $ThisUser = $_COOKIE['statusUsername'];
    //remove the $user from the Following of $ThisUser
    //remove ThisUser from the followers array of user
    $json_obf = file_get_contents("networksJSON.json");
    $data = json_decode($json_obf, true);
    $arr = array();
    for ($i = 0;$i < count($data[$ThisUser]['Following']);$i++) {
        if ($data[$ThisUser]['Following'][$i] != $user) {
            array_push($arr, $data[$ThisUser]['Following'][$i]);
        }
    }
    $data[$ThisUser]['Following'] = $arr;
    //if(in_array($user,$data[$ThisUser]['Following']))
    //	$data[$ThisUser]['Following']=array_diff($data[$ThisUser]['Following'], array($user));
    $arr = array();
    for ($i = 0;$i < count($data[$user]['Followers']);$i++) {
        if ($data[$user]['Followers'][$i] != $ThisUser) {
            array_push($arr, $data[$user]['Followers'][$i]);
        }
    }
    $data[$user]['Followers'] = $arr;
    //if(in_array($ThisUser,$data[$user]['Followers']))
    //	$data[$user]['Followers']=array_diff($data[$user]['Followers'], array($ThisUser));
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $userToFollow = $user;
    $serverName = "xxxxxxxxxxxxx";
    $connectionOptions = array("Database" => "xxxxxxxxx", "Uid" => "xxxxxxxxxx", "PWD" => "xxxxxxxxxxx");
    $conn = sqlsrv_connect($serverName, $connectionOptions) or die(print_r(sqlsrv_errors(), true));
    //database update for followings for $ThisUSer
    $tsql = "SELECT followings FROM dbo.allUsersInfo WHERE username='$ThisUser'";
    $getResults = sqlsrv_query($conn, $tsql);
    if (!$getResults) {
        $msg['msg'] = "serverError";
        exit();
    }
    $row = sqlsrv_fetch_array($getResults);
    $followings = $row['followings'] - 1;
    if ($followings < 0) $followings = 0;
    $sql = "UPDATE dbo.allUsersInfo SET followings='$followings' WHERE username='$ThisUser' ";
    $getResults = sqlsrv_query($conn, $sql);
    if ($getResults == FALSE) {
        $msg['msg'] = "serverError2";
        echo $msg;
        exit();
    }
    //databse update for followers of $userToFollow
    $tsql = "SELECT followers FROM dbo.allUsersInfo WHERE username='$userToFollow'";
    $getResults = sqlsrv_query($conn, $tsql);
    if (!$getResults) {
        $msg['msg'] = "serverError";
        exit();
    }
    $row = sqlsrv_fetch_array($getResults);
    $followers = $row['followers'] - 1;
    if ($followers < 0) $followers = 0;
    $sql = "UPDATE dbo.allUsersInfo SET followers='$followers' WHERE username='$userToFollow' ";
    $getResults = sqlsrv_query($conn, $sql);
    if ($getResults == FALSE) {
        $msg['msg'] = "serverError2";
        echo $msg;
        exit();
    }
}
if (empty($_POST['name'])) {
    $msg['msg'] = "emptyValue";
} else {
    //$msg['msg']=$_POST['name'];
    if ($_POST['type'] == "Follow") addUserToJson($_POST['name']);
    else unfollowUserAndRemoveFromJson($_POST['name']);
}
echo json_encode($msg);
?>
