<?php
//Embedding DBConn.php
include 'DBConn.php';
session_start();
//If submit button is clicked
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']); //Declaring variable to store username
    $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); //Declaring variable to store password

    //SQL query to select username and password from user table
    $select_users = mysqli_query($conn, "SELECT * FROM `tbluser` WHERE email = '$username' AND password = '$pass'")
        or die('query failed');

    //SQL query to select verify value from table user    
    $select_verify = mysqli_query($conn, "SELECT verify FROM `tbluser` WHERE email = '$username' AND password = '$pass'")
        or die('select verify query failed');

    //SQL query to select verify value from table admin
    $select_admin = mysqli_query($conn, "SELECT * FROM `tbladmin` WHERE email = '$username' AND password = '$pass'") or die('query failed');


    //If select users query has contains rows greater than 0    
    if (mysqli_num_rows($select_users) > 0) {

        //While loop to loop through the select very query
        while ($fetch_verify = mysqli_fetch_assoc($select_verify)) {
            //If verify is equal to 0
            if ($fetch_verify['verify'] == 0) {
                //Display message not verified to user
                $message[] = 'User is not verified!';
            } else {
                $row = mysqli_fetch_assoc($select_users);
                $rowtwo = mysqli_fetch_assoc($select_admin);

                $_SESSION['user_name'] = $row['firstname'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];

                $_SESSION['admin_name'] = $rowtwo['firstname'];
                $_SESSION['admin_email'] = $rowtwo['email'];
                $_SESSION['admin_id'] = $rowtwo['id'];
                //redirect user to home page
                header('location:home.php');
            }
        }
    } else {
        //Display to user if not registered
        $message[] = 'User does not exist!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/styletwo.css">

    <title>Login</title>
</head>

<body>
    <?php
    //if statement to display sticky message
    if (isset($message)) {
        //foreach loop to loop through message
        foreach ($message as $message) {
            echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
    }

    ?>

    <!-- Container-->
    <div class="container">
        <!-- Container for logo-->
        <div class="logo_container">
            <img src="images/infinitybooks.png" alt="Book Store Logo">
        </div>
        <!-- Container for form-->
        <div class="form-container">
            <form action="" method="post">
                <h3>Login</h3>
                <input type="text" name="username" placeholder="enter your username" class="box" required>
                <input type="password" name="password" placeholder="enter your password" class="box" required>
                <input type="submit" name="submit" value="login now" class="btn">
                <p>haven't registered yet? <a href="register.php">register now</a></p>
                <p>login as admin <a href="adminlogin.php">login here</a></p>
            </form>
        </div>
    </div>

</body>

</html>