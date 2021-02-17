<?php
if ($_COOKIE['status'] == '1') {
} else {
    echo ("
        <script>
        window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
        </script>");
}
$username = $_COOKIE['statusUsername'];
$data_img = $_FILES['file']['tmp_name'];
$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
$json_obf = file_get_contents("networksJSON.json");
$data = json_decode($json_obf, true);
$json_obf_posts = file_get_contents("networkPosts.json");
$data_posts = json_decode($json_obf_posts, true);
$noOfPosts = count($data[$username]['UserPosts']);
$noOfPosts++;
$target_file = 'uploads/posts' . $username . $noOfPosts . '.jpg';
$target_fileC = 'posts' . $username . $noOfPosts . '.jpg';
$title = $_POST['title'];
$desc = $_POST['desc'];
$date=$_POST['date'];
if ($ext == 'PNG' || $ext == 'png') {
    $target_file_temp = 'uploads/posts' . $username . $noOfPosts . '.png';
    if (move_uploaded_file($data_img, $target_file_temp)) {
        $img = imagecreatefrompng($target_file_temp);
        imagejpeg($img, $target_file, 60);
        unlink($target_file_temp);
        $data_posts[$target_fileC] = array("likes" => 0, "title" => $title, "desc" => $desc, "user" => $username,"date"=>$date);
        $json_object1 = json_encode($data_posts);
        file_put_contents('networkPosts.json', $json_object1);
        $data[$username]['UserPosts'][$target_fileC] = array("likes" => 0, "title" => $title, "desc" => $desc,"date"=>$date);
        $json_object = json_encode($data);
        file_put_contents('networksJSON.json', $json_object);
        $returnObject['image'] = $target_fileC;
        $returnObject['title'] = $title;
        $returnObject['desc'] = $desc;
        echo json_encode($returnObject);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    $image = imagecreatefromjpeg($data_img);
    imagejpeg($image, $target_file, 60);
    $data_posts[$target_fileC] = array("likes" => 0, "title" => $title, "desc" => $desc, "user" => $username,"date"=>$date);
    $json_object1 = json_encode($data_posts);
    file_put_contents('networkPosts.json', $json_object1);
    $data[$username]['UserPosts'][$target_fileC] = array("likes" => 0, "title" => $title, "desc" => $desc,"date"=>$date);
    $json_object = json_encode($data);
    file_put_contents('networksJSON.json', $json_object);
    $returnObject = array();
    $returnObject['image'] = $target_fileC;
    $returnObject['title'] = $title;
    $returnObject['desc'] = $desc;
    echo json_encode($returnObject);
}
?> 
