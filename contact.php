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

//If send button is clicked 
if (isset($_POST['send'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']); //Declaring variable to store custmoer name
   $email = mysqli_real_escape_string($conn, $_POST['email']); //Declaring variable to store custmoer email
   $number = $_POST['number']; //Declaring variable to store custmoer number
   $msg = mysqli_real_escape_string($conn, $_POST['message']); //Declaring variable to store custmoer message

   //SQL query to select all records from tblmessages table
   $select_message = mysqli_query($conn, "SELECT * FROM `tblmessages` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   //If select query contains rows greater than 0
   if (mysqli_num_rows($select_message) > 0) {
      //Display message to the user
      $message[] = 'message sent already!';
   } else {
      //SQL query to insert record on the tblmessages table
      mysqli_query($conn, "INSERT INTO `tblmessages`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      //Display message to the user
      $message[] = 'message sent successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>contact us</h3>
      <p> <a href="home.php">home</a> / contact </p>
   </div>

   <section class="contact">

      <form action="" method="post">
         <h3>say something!</h3>
         <input type="text" name="name" required placeholder="enter your name" class="box">
         <input type="email" name="email" required placeholder="enter your email" class="box">
         <input type="number" name="number" required placeholder="enter your number" class="box">
         <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>