<?php
		if($_COOKIE['status']=='1'){
        
        
        }
      
	      else{
	        echo("
	            <script>
	            window.location.href = 'https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php';
	            </script>");
	      }

		

		function LikeThisPostAPI($imageTobeLiked){
			$username=$_COOKIE['statusUsername'];
			$json_obf=file_get_contents("networksJSON.json");
			$data=json_decode($json_obf,true);
	
			$json_obf_posts=file_get_contents("networkPosts.json");
			$data_posts=json_decode($json_obf_posts,true);
		
			if(in_array($imageTobeLiked,$data[$username]['LikedPosts'])){
				echo "error";exit();
			}
			else{
				array_push($data[$username]['LikedPosts'],$imageTobeLiked);
					
				$json_object = json_encode($data);
				file_put_contents('networksJSON.json', $json_object); 

				 
				if(isset($data_posts[$imageTobeLiked])){
					$prevLiked=$data_posts[$imageTobeLiked]["likes"];
					$prevLiked++;
					$data_posts[$imageTobeLiked]=array("likes"=>$prevLiked,"title"=>$_POST['title'],"desc"=>$_POST['desc'],"user"=>"NASAAPIs");
				}
				else{
					$data_posts[$imageTobeLiked]=array("likes"=>"1","title"=>$_POST['title'],"desc"=>$_POST['desc'],"user"=>"NASAAPIs");
				}
				

				$json_object1 = json_encode($data_posts);
				file_put_contents('networkPosts.json', $json_object1); 
				echo "added to posts"; 
			}
			
		}

		if(isset($_POST['image'])){
			LikeThisPostAPI($_POST['image']);
		}
		
		

?>  