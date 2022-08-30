<?php
//Embedding DBConn.php
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

//If add to cart button is clicked
if (isset($_POST['add_to_cart'])) {
   $product_name = $_POST['product_name']; //Declaring variable to store book name
   $product_price = $_POST['product_price']; //Declaring variable to store book price
   $product_image = $_POST['product_image']; //Declaring variable to store book image
   $product_quantity = $_POST['product_quantity']; //Declaring variable to store book quantity

   //SQL query to select all records from tblcart table
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `tblcart` WHERE name = '$product_name' AND id = '$user_id'") or die('select from cart failed');

   //If select query contains rows greater than 0
   if (mysqli_num_rows($check_cart_numbers) > 0) {
      //Display message to the user
      $message[] = 'already added to cart!';
   } else {
      //SQL query to insert record on the tblcart table 
      mysqli_query($conn, "INSERT INTO `tblcart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('insert into cart failed');
      //Display message to the user
      $message[] = 'product added to cart!';
   }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>our shop</h3>
      <p> <a href="home.php">home</a> / shop </p>
   </div>

   <section class="products">

      <h1 class="title">latest products</h1>

      <div class="box-container">

         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `tblbooks`") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="post" class="box">
                  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="price">R<?php echo $fetch_products['price']; ?></div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>
      </div>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>