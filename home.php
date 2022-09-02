<?php
session_start();
require_once('config/Database.php');
$db_handle = new Database();

if(isset($_POST['add'])) {
    if(isset($_REQUEST['books'])){

    	foreach($_REQUEST['books'] as $key=>$value){

    			$bookByID = $db_handle->runQuery("SELECT * FROM books WHERE book_id=$value");

    			if(!empty($bookByID[0]["book_name"]) && !empty($bookByID[0]["code"]) && 
    				!empty($bookByID[0]["class"]) && !empty($bookByID[0]["price"])) {

    			$itemArray = array($value=>array('book_name'=>$bookByID[0]["book_name"], 'book_id'=>$bookByID[0]["book_id"],'code'=>$bookByID[0]["code"], 'class'=>$bookByID[0]["class"], 'quantity'=>1, 'price'=>$bookByID[0]["price"]));
    			
    			if(!empty($_SESSION["cart_item"])) {
    				if(in_array($bookByID[0]["book_id"],array_keys($_SESSION["cart_item"]))) {
    					foreach($_SESSION["cart_item"] as $k => $v) {
    							if($bookByID[0]["book_id"] == $k) {
    								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
    									$_SESSION["cart_item"][$k]["quantity"] = 0;
    								}
    								$_SESSION["cart_item"][$k]["quantity"] += 1;
    							}
    					}
    				} else {
    					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
    				}
    			} else {
    				$_SESSION["cart_item"] = $itemArray;
    			}
    		}
    	}
    }

} elseif (isset($_POST['update'])) {
	if(!empty($_SESSION["cart_item"])) {
        $qua=$_REQUEST['quantity'];
        $iid=$_REQUEST['id'];
		foreach($_SESSION["cart_item"] as $k => $v) {
            foreach($qua as $q => $num ){
                foreach($iid as $i => $val ){
                    if (intval($v['book_id']) == intval($val) &&
                        $v['quantity'] == intval($num) ) {
						$id = str_replace('quantity*', '', $k);
                        $quantity = (int)$v;
                        if (is_numeric($id) && 
                            isset($_SESSION['cart_item'][$id]) && 
                             $quantity > 0) {
                                $_SESSION['cart_item'][$id]['quantity'] = intval($num);
                        }
                    }
                }
            }
		}
				
    } else {
	   $_SESSION["cart_item"] = $itemArray;
    }

}

if(isset($_GET['remove'])){

   if(!empty($_SESSION["cart_item"])) {

        foreach($_SESSION["cart_item"] as $key => $value) {
            $id=intval($value['book_id']);
            if($_GET["code"] == $id)
                unset($_SESSION["cart_item"][$key]);              
            if(empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    }

} elseif(isset($_GET['empty'])){
		unset($_SESSION["cart_item"]);
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NewVision BookStore">
    <meta name="keywords" content="NewVision BookStore">
    <meta name="author" content="NewVision BookStore">
    <title>NewVision Book Shopping Online</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">

    <!-- Website css -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Dropdown css -->
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
</head>
<body>

    <!-- Title Section Start -->
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">

                <div class="col-xxl-12 col-xl-12">
                    <div class="title title-flex">
                        <div>
                            <h2>NewVision Book Order Form</h2>
                            <span class="title-leaf">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Title Section End -->

<!-- Form section Start -->
    <section class="checkout-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
               <div class="tab-pane" id="d-options" role="tabpanel">
                            <form method="post" action="post-order/"  class="row g-4">
                                <div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check custom-form-check">
                                                            <label class="form-check-label" for="standard">Full Name</label>
                                                        </div>
                                            </div>

                                            <div class="col-xxl-4 col-sm-6">
                                                            <div class="form-floating theme-form-floating date-box">
                                                                <input name="customer_name" type="text" class="form-control" required>
                                                            </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check custom-form-check">
                                                            <label class="form-check-label" for="sameDay">Email</label>
                                                        </div>
                                            </div>

                                            <div class="col-xxl-4 col-sm-6">
                                                 <div class="form-floating theme-form-floating date-box">
                                                                <input name="email" type="email" class="form-control" required>
                                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check custom-form-check">
                                                            <label class="form-check-label" for="sameDay">Phone number</label>
                                                        </div>
                                            </div>

                                            <div class="col-xxl-4 col-sm-6">
                                                 <div class="form-floating theme-form-floating date-box">
                                                                <input placeholder="0700-000000 or 0392-000000" name="phone_number" type="tel" class="form-control" required>
                                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check custom-form-check">
                                                            <label class="form-check-label" for="sameDay">Delivery Location</label>
                                                        </div>
                                            </div>

                                            <div class="col-xxl-4 col-sm-6">
                                                 <div class="form-floating theme-form-floating date-box">
                                                                <input name="location" type="text" class="form-control" required>
                                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check mb-0 custom-form-check">
                                                            <label class="form-check-label" for="future">Select Books</label>
                                                        </div>
                                            </div>

                                            <div class="col-xxl-2 col-sm-4">
                                                <div class="form-floating theme-form-floating">
                                                        <select class="form-select chosen-select" data-placeholder="Begin typing a name to filter..." multiple name="books[]" required>
                                                        	<?php
                                                            	$book_array = $db_handle->runQuery("SELECT * FROM books ORDER BY book_id ASC");
                                                            	if (!empty($book_array)) { 
                                                            		foreach($book_array as $key=>$value){
                                                            ?>
                                                                <option value="<?php echo $book_array[$key]["book_id"]; ?>">
                                                                    <?php echo $book_array[$key]["book_name"]; ?></option>
                                                            <?php } } ?>
                                                        </select><caption><i  style="color: darkred;">Select books and click Submit at the bottom to complete order. Cart is not yet fully functional.</i></caption>
                                                </div>

                                            </div>

                                            <div class="col-xxl-2 col-sm-2">
                                                <div class="form-floating theme-form-floating">
                                                    <input type="submit" formaction="home?action=add" name="add" value="Update Cart" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

<div class="col-12">
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                            <div class="col-xxl-4 col-sm-6">
                                                        <div class="form-check custom-form-check">
                                                            <label class="form-check-label" for="sameDay">Additional Comment</label>
                                                        </div>
                                            </div>
                                             <div class="col-xxl-4 col-sm-6">
                                                 <div class="form-floating theme-form-floating date-box ">
                                                                <textarea placeholder="Add a note..." name="comment" rows="4" cols="50" class="form-control" required></textarea>
                                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">                                    
                                    <div class="delivery-option">
                                        <div class="row g-4">
                                    		<div id="shopping-cart">
                                                <div class="col-xxl-4 col-sm-6">
                                                    <div class="mb-0 custom-form-check">
                                                        <label class="title" for="future"><h2>Your Cart</h2></label>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-4 col-sm-6">
                                                    <div class="mb-0 custom-form-check">
                                                        <a id="btnEmpty" href="home?empty">Empty Cart</a>
                                                    </div>
                                                </div>

		                                      
<?php
    if(isset($_SESSION["cart_item"])){
        $total_quantity = 0;
        $total_price = 0;
?>	
                <table class="tbl-cart" cellpadding="10" cellspacing="1" bgcolor="#ececec">
                    <tbody>
                            <tr>
                            <th><label for="name">
                                                    Name
                                                </label></th>
                            <th><label for="quantity">
                                                    Quantity
                                                </label></th>
                            <th><label for="unitprice">
                                                    Unit Price
                                                </label></th>
                            <th><label for="price">
                                                    Price
                                                </label></th>
                            <th>Remove</th>
                            </tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
?>
                        <tr>
                            <td><input type="text" value="<?php echo $item['book_name']; ?>" /></td>

                            <td><input type="number" name="quantity[]" value="<?=$item['quantity']?>" min="1" max="" placeholder="Quantity" required></td>

                            <td><input type="text" value="<?php echo "$ ".$item["price"]; ?>" /></td>

                            <td><input type="text" value="<?php echo "$ ". number_format($item_price,2); ?>" /></td>

                            <td><a href="home?remove&code=<?php echo $item["book_id"]; ?>" class="btnRemoveAction"><img src="assets/images/icon-delete.png" alt="Remove Item" /></a></td>
                            <input type="hidden" value="<?php echo $item["book_id"]; ?>" name="id[]" />
                        </tr>
<?php
        $total_quantity += $item["quantity"];
        $total_price += ($item["price"]*$item["quantity"]);
    }
?>

                        <tr>
                            <td colspan="2" align="right">Total:</td>

                            <td align="right"><?php echo $total_quantity; ?></td>

                            <td align="right" colspan="2"><strong><?php echo "UGX ".number_format($total_price, 2); ?></strong></td>
                        </tr>

                        <tr>
                            <td><input type="submit" formaction="home?&code=<?php echo $item["book_id"]; ?>" value="update" name="update" class="btn btn-light proceed-btn save"></td>
                        </tr>
                </tbody>
            </table>
<?php
    } else {
?>

            <div class="no-records">
                <i>
                    <h5>Cart is Empty. Add some books!</h5>
                </i>
                <i style="color: darkred;">WIP:Cart not yet fully functional!</i>
            </div>

<?php 
    }
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <section class="product-section bottom-buttons">
                                    <div class="container-fluid-bottom">
                                        <div class="row g-sm-4 g-3">

                                            <div class="col-xxl-12 col-xl-12">
                                                <div class="title title-flex">
                                                    
                                                    <div class="button-group">
                                                            <ul class="button-group-list">
                                                                <li>
                                                                        <input name="submit" value="submit" type="submit" class="btn btn-light proceed-btn save">
                                                                </li>

                                                                <li>
                                                                    <input value="Cancel" type="reset" class="btn btn-animation backward-btn">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Form section End -->


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>

        <script>
           $(".chosen-select").chosen({
          no_results_text: "Oops, nothing found!"
        });

        </script>
    </body>
</html>