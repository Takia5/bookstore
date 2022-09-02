<?php
class Book{ 
    
    private $conn;
    private $customerTable = "customer"; 
    private $orderTable = "customer_orders"; 
    private $detailsTable = "order_details";     
    public $customer_name;
    public $email;
    public $phone_number;
    public $location;
    public $books;
    public $customer_id; 
    public $comment;
    
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function read(){				
		return '';	
	}
	
	function create(){
		//Add new customer
		$customer="
			INSERT INTO customer(customer_name, email, phone_number, location)
			VALUES(?,?,?,?)"; 
			//if (true) {
			$cust = $this->conn->prepare($customer);
		$this->customer_name = htmlspecialchars(strip_tags($this->customer_name));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
		$this->location = htmlspecialchars(strip_tags($this->location));
		$cust->bind_param("ssss", $this->customer_name, $this->email, $this->phone_number, $this->location);
		$cust->execute();
		$id = mysqli_insert_id($this->conn);


		//Add new order
		$order= "INSERT INTO customer_orders (customer_id, comment)
			VALUES(?,?)";
		$ord = $this->conn->prepare($order);
		$this->customer_id = $id;
		$this->comment = htmlspecialchars(strip_tags($this->comment));
		
		$ord->bind_param("is", $this->customer_id, $this->comment);
		$ord->execute();
		$orderID = mysqli_insert_id($this->conn);

		//Add order details
		foreach ($this->books as $key => $value) {
			$details = "INSERT INTO order_details(book_id, order_id) VALUES(?,?)";
		}

		$stmt = $this->conn->prepare($details);
		$this->book_id = $value;
		$this->order_id = $orderID;
			
		$stmt->bind_param("ii", $this->book_id, $this->order_id);

		if($stmt->execute()){
			return true;
		}
				 
		return false;		 
	}
		
	function update(){	 
		return false;
	}
	
	function delete(){
		return false;		 
	}
}
?>