<?php
//Embedding DBConn.php file for database connection 
include 'DBConn.php';

//Starting session
session_start();

//Declaring variable to store user_id from session
$user_id = $_SESSION['user_id'];

//If user_id is null
if (!isset($user_id)) {
   //Redirect user to login page
   header('location:login.php');
}

//If order button is clicked
if (isset($_POST['order_btn'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']); //Declaring variable to store customer name 
   $number = $_POST['number']; //Declaring variable to store customer number
   $email = mysqli_real_escape_string($conn, $_POST['email']); //Declaring variable to store customer email
   $method = mysqli_real_escape_string($conn, $_POST['method']); //Declaring variable to store customer payment method
   //Declaring variable to store customer address 
   $address = mysqli_real_escape_string($conn, 'house no. ' . $_POST['house'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y'); //Declaring variable to store customer order date
   $paymentstatus = 'pending'; //Declaring variable to store customer payment status
   $cart_total = 0; //Declaring variable to store total cart amount
   $cart_products[] = ''; //Declaring array to store ordered books

   //Sql query to select all records from tblcart 
   $cart_query = mysqli_query($conn, "SELECT * FROM `tblcart` WHERE user_id = '$user_id'") or die('query failed');

   //If select query contains rows greater than 0 
   if (mysqli_num_rows($cart_query) > 0) {
      //while loop to loop through the shopping 
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') '; //Adding book name and quantity to cart products 
         $sub_total = ($cart_item['price'] * $cart_item['quantity']); //multiplying price by the quantity 
         $cart_total += $sub_total; //adding to total price 
      }
   }
   //imploding total products to cart 
   $total_products = implode(', ', $cart_products);

   //Sql query to select all records from tblorder
   $order_query = mysqli_query($conn, "SELECT * FROM `tblorder` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   //If cart total is equal 0 
   if ($cart_total == 0) {
      $message[] = 'your cart is empty'; //Display message to the user
   } else {
      //If select query contains rows greater than 0 
      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'order already placed!'; //Display message to the user
      } else {
         //Sql query to insert into tblorder
         mysqli_query($conn, "INSERT INTO `tblorder`(user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$paymentstatus')") or die('query failed');
         $message[] = 'order placed successfully!'; //Display message to the user 
         //Sql query to delete from tblcart
         mysqli_query($conn, "DELETE FROM `tblcart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>checkout</h3>
      <p> <a href="home.php">home</a> / checkout </p>
   </div>

   <section class="display-order">

      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `tblcart` WHERE user_id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select_cart) > 0) {
         while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
      ?>
            <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo 'R' . $fetch_cart['price'] . ' | ' . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
      <?php
         }
      } else {
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
      <div class="grand-total"> grand total : <span>R<?php echo $grand_total; ?></span> </div>

   </section>

   <section class="checkout">

      <form action="" method="post">
         <h3>place your order</h3>
         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" required placeholder="enter your name">
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="number" name="number" required placeholder="enter your number">
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" required placeholder="enter your email">
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method">
                  <option value="credit card">credit/debit card</option>
                  <option value="cash on delivery">cash on delivery</option>
               </select>
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="number" min="0" name="house" required placeholder="e.g. house no.">
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="text" name="street" required placeholder="e.g. street name">
            </div>
            <div class="inputBox">
               <span>city :</span>
               <input type="text" name="city" required placeholder="e.g. cape town">
            </div>
            <div class="inputBox">
               <span>province :</span>
               <input type="text" name="state" required placeholder="e.g. western cape">
            </div>
            <div class="inputBox">
               <span>country :</span>
               <input type="text" name="country" required placeholder="e.g. south africa">
            </div>
            <div class="inputBox">
               <span>postal code :</span>
               <input type="number" min="0" name="pin_code" required placeholder="e.g. 77899">
            </div>
         </div>
         <input type="submit" value="order now" class="btn" name="order_btn">
      </form>

   </section>









   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>