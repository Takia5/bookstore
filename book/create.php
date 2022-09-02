<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/book.php';
 
$database = new Database();
$db = $database->getConnection();
 
$book = new Book($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->customer_name) && !empty($data->email) &&
!empty($data->phone_number) && !empty($data->location) &&
!empty($data->books) && !empty($data->comment)){    

    $book->customer_name = $data->customer_name;
    $book->email = $data->email;
    $book->phone_number = $data->phone_number;
    $book->location = $data->location;
	$book->books = $data->books;
    $book->comment = $data->comment; 
    
    if($book->create()){         
        http_response_code(201);  
        $success_msg = '
                    <html>
                    <head>
                    </head>
                    <body>
                          <p><h1>
                          Order submitted successfully!
                          </p></h1>
                    </body>
                    </html>';       
        echo ($success_msg);
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to complete Order."));
    }
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to complete Order. Data is incomplete."));
}
?>