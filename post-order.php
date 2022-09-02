<?php
	include_once 'config/Database.php';
	$database = new Database();
	$db = $database->getConnection();


	function requestApi($method, $apiUrl, $data){
	   $curl = curl_init();
	   switch ($method){
	      case "POST":
	         curl_setopt($curl, CURLOPT_POST, 1);
	         if ($data)
	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	         break;
	      case "PUT":
	         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	         if ($data)
	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                              
	         break;
	      default:
	         if ($data)
	            $apiUrl = sprintf("%s?%s", $apiUrl, http_build_query($data));
	   }
	   
	   curl_setopt($curl, CURLOPT_URL, $apiUrl);
	   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	      'APIKEY: 123456789',
	      'Content-Type: application/json',
	   ));
	   
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	   
	   $resultData = curl_exec($curl);
	   if(!$resultData){
	        die("Invalid API Request!");
	    }
	   curl_close($curl);
	   return $resultData;
	}
				
		// Getting form data
	if(isset($_REQUEST['customer_name'], $_REQUEST['email'], 
		$_REQUEST['phone_number'], $_REQUEST['location'], 
		$_REQUEST['books'], $_REQUEST['comment'] )){

		$customer_name = $_REQUEST['customer_name'];
		$email = $_REQUEST['email'];
		$phone_number = $_REQUEST['phone_number'];
		$location = $_REQUEST['location'];
		$books = $_REQUEST['books'];
		$comment = $_REQUEST['comment'];

		$postData = array (
			"customer_name"=> $customer_name,
			"email"=>$email,
			"phone_number"=>$phone_number,
			"location" => $location,
			"books" => $books,
			"comment" => $comment,
		);

		$response = 
		requestApi(
			'POST', 'http://localhost/bookstore/book/create.php',
			 json_encode($postData));

		if($response){

			echo($response);

			$book_list="";

			foreach($books as $k => $book){
				$sql = mysqli_query($db,"SELECT * FROM books WHERE book_id = $book");
            while ($row = mysqli_fetch_array($sql)) {
              $book_list = $book_list.', '.$row['book_name'];
            }
			}

			$msg = '
					<html>
					<head>
					</head>
					<body>
					  <p><h1>You have received a new order from '.$customer_name.'!</h1></p>
					  <table>
					    <tr>
					      <th>Name</th><th>Phone Number</th><th>Email</th><th>Books</th>
					    </tr>
					    <tr>
					      <td>'.$customer_name.'</td><td>'.$phone_number.'</td><td>'.$email.'</td><td>'.$book_list.'</td>
					    </tr>
					  </table>
					  <p><h2>Additional Comment</h2></p>
					  <p><h3>'.$comment.'</h3></p>
					</body>
					</html>
					';

					$to = "publishing@newvision.co.ug";
					$subject = "New Order from NewVision Online BookStore!";
					$headers = "From: ".$email."\n";
					$message = $msg;

					mail($to,$subject,$message,$headers);

		} else{
			$fail_msg = '
					<html>
					<head>
					</head>
					<body>
						  <p><h1>
						  Could not Submit Order! Please try again.
						  </p></h1>
					</body>
					</html>
					';
			echo($fail_msg);
		}
	} else {
		$duplicate_msg = '
					<html>
					<head>
					</head>
					<body>
						  <p><h1>
						  Order already submitted! Please make a new order.
						  </p></h1>
					</body>
					</html>
					';
		echo($duplicate_msg);
	}
?>
