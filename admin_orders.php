<?php
//Embedding DBConn.php file for database connection 
include 'DBConn.php';

//Starting session
session_start();

//Declaring variable to store admin_id from session
$admin_id = $_SESSION['admin_id'];

//If update button is clicked 
if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id']; //Declaring variable to store update id
    $update_payment = $_POST['update_payment']; //Declaring variable to store update payment 

    //SQL query to update record on the tblorder table
    mysqli_query($conn, "UPDATE `tblorder` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
    //Displaying message to the user
    $message[] = 'payment status has been updated!';
}

//If delete button is clicked
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete']; //Declaring variable to store delete id

    //SQL query to delete record on the tblorder table
    mysqli_query($conn, "DELETE FROM `tblorder` WHERE id = '$delete_id'") or die('query failed');
    //Redirect user to the admin orders page
    header('location:admin_orders.php');
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

    <title>Orders</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <section class="orders">

        <h1 class="title">placed orders</h1>

        <div class="box-container">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `tblorder`") or die('query failed');
            if (mysqli_num_rows($select_orders) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
            ?>
                    <div class="box">
                        <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                        <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                        <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
                        <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                        <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
                        <p> total products : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                        <p> total price : <span>R<?php echo $fetch_orders['total_price']; ?></span> </p>
                        <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="completed">completed</option>
                            </select>
                            <input type="submit" value="update" name="update_order" class="option-btn">
                            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
                        </form>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no orders placed yet!</p>';
            }
            ?>
        </div>

    </section>

    <!--Admin page js link-->
    <script src="js/admin_script.js"></script>

</body>

</html>