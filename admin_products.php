<?php
//Embedding DBConn.php
include 'DBConn.php';

//Starting session
session_start();

//Declaring variable to store admin_id from session
$admin_id = $_SESSION['admin_id'];


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

//If delete buttton is clicked 
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete']; //Declaring variable to store delete id

    //SQL query to delete record on the tblorder table
    mysqli_query($conn, "DELETE FROM `tblbooks` WHERE id = '$delete_id'") or die('query failed');
    //Redirect user to the admin products page
    header('location:admin_products.php');
}

//If update buttton is clicked 
if (isset($_POST['update_product'])) {

    $update_p_id = $_POST['update_p_id']; //Declaring variable to store update id
    $update_name = $_POST['update_name']; //Declaring variable to store update name
    $update_price = $_POST['update_price']; //Declaring variable to store update price

    //SQL Query to update record on the tblbooks table
    mysqli_query($conn, "UPDATE `tblbooks` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

    $update_image = $_FILES['update_image']['name']; //Declaring variable to store image
    $update_image_tmp_name = $_FILES['update_image']['tmp_name']; //Declaring variable to store temporary image name
    $update_image_size = $_FILES['update_image']['size']; //Declaring variable to store image size
    $update_folder = 'uploaded_img/' . $update_image; //Declaring variable to store image folder
    $update_old_image = $_POST['update_old_image']; //Declaring variable to store old image

    //If image is null
    if (!empty($update_image)) {
        //If image size is greater than 2000000
        if ($update_image_size > 2000000) {
            //Displaying message to the user 
            $message[] = 'image file size is too large';
        } else {
            //SQL query to update record on the tblbooks table
            mysqli_query($conn, "UPDATE `tblbooks` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }
    //Redirect user to admin products page
    header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--font awesome style link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!--admin page style sheet-->
    <link rel="stylesheet" href="css/admin_style.css">

    <title>Books</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <!-- product section starts -->
    <section class="add-products">
        <h1 class="title">Infinity Books products</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <h3>add book</h3>
            <input type="text" name="name" class="box" placeholder="enter book name" required>
            <input type="number" name="price" class="box" placeholder="enter book price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
            <input type="submit" value="add book" name="add_book" class="btn">
        </form>
    </section>
    <!-- product section ends -->

    <!-- show books -->

    <section class="show-products">

        <div class="box_container">

            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `tblbooks`") or die('Quesry failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {

            ?>
                    <div class="box">
                        <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">

                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="price"><?php echo $fetch_products['price']; ?></div>
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this book?')">delete</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no books added yet</p>';
            }
            ?>
        </div>
    </section>
    <!-- show books ends -->

    <!-- edit books starts -->
    <section class="edit-product-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id = $_GET['update'];
            $update_query = mysqli_query($conn, "SELECT * FROM `tblbooks` WHERE id = '$update_id'") or die('query failed');
            if (mysqli_num_rows($update_query) > 0) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {

        ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                        <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                        <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter book name">
                        <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter book price">
                        <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                        <input type="submit" value="update" name="update_product" class="btn">
                        <input type="reset" value="cancel" id="close-update" class="option-btn">
                    </form>
        <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
        }
        ?>
    </section>
    <!-- edit books ends -->

    <!--Admin page js link-->
    <script src="js/admin_script.js"></script>

</body>

</html>