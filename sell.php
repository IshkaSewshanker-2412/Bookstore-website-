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

//If add product button is clicked
if (isset($_POST['add_book'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']); //Declaring variable to store name
    $price = $_POST['price']; //Declaring variable to store price
    $image = $_FILES['image']['name']; //Declaring variable to store image
    $image_size = $_FILES['image']['size']; //Declaring variable to store image size
    $image_tmp_name = $_FILES['image']['tmp_name']; //Declaring variable to store temporary image name
    $image_folder = 'uploaded_img/' . $image; //Declaring variable to store image folder

    //SQL query to name from table books
    $select_product_name = mysqli_query($conn, "SELECT name FROM `tblbooks` WHERE name = '$name'");

    //If select product name query contains rows greater than 0
    if (mysqli_num_rows($select_product_name) > 0) {
        //Displaying messsage to the user
        $message[] = 'product name already added';
    } else {
        //SQL query to insert values into table books 
        $add_product_query = mysqli_query($conn, "INSERT INTO `tblbooks`(name, price, image) VALUES('$name','$price','$image')");

        //If add product query has rows
        if ($add_product_query) {
            //If image size is greater than 2000000
            if ($image_size > 2000000) {
                //Display message to the user
                $message[] = 'image size is too large';
            } else {
                //move temporary image to image folder uploaded_img
                move_uploaded_file($image_tmp_name, $image_folder);
                //Displaying message to the user
                $message[] = 'product added succesfully!';
            }
        } else {
            //Displaying message to the user
            $message[] = 'product could not be added';
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
    <title>sell book</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>request to sell book</h3>
        <p> <a href="home.php">home</a> / sell </p>
    </div>

    <section class="contact">

        <form action="" method="post">
            <h3>sell book!</h3>
            <input type="text" name="name" class="box" placeholder="enter book name" required>
            <input type="number" name="price" class="box" placeholder="enter book price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="sell" name="add_book" class="btn">
        </form>

    </section>








    <?php include 'footer.php'; ?>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>

</html>