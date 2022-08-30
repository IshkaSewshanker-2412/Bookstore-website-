<?php
//Embedding DBConn.php
include 'DBConn.php';

//Starting session
session_start();

//Declaring variable to store admin_id from session
$admin_id = $_SESSION['admin_id'];

//If delete button is clicked 
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete']; //Declaring variable to store delete id

    //SQL query to delete from table user
    mysqli_query($conn, "DELETE FROM `tbluser` WHERE id = '$delete_id'") or die('query failed');
    //redirecting user to admin_user
    header('location:admin_users.php');
}

//If update button is clicked
if (isset($_POST['update_product'])) {
    $update_u_id = $_POST['update_u_id']; //Declaring variable to store update id
    $update_fisrtname = $_POST['update_firstname']; //Declaring variable to store firstname
    $update_surname = $_POST['update_surname']; //Declaring variable to store surname
    $update_studentnumber = $_POST['update_studentNumber']; //Declaring variable to store student number
    $update_username = $_POST['update_username']; //Declaring variable to store username
    $update_pass = md5($_POST['update_password']); //Declaring variable to store password
    $update_cpass = md5($_POST['update_cpassword']); //Declaring variable to store confirm password

    //SQL query to update table user
    mysqli_query($conn, "UPDATE `tbluser` SET firstname = '$update_fisrtname', surname = '$update_surname'
    , studentNumber = '$update_studentnumber', email = '$update_username' WHERE id = '$update_u_id'") or die('query failed');

    //Redirect user to admin users
    header('location:admin_users.php');
}
//If verify user is clicked
if (isset($_POST['verify_user'])) {
    $update_u_id = $_POST['verify_u_id']; //Declaring variable to store update id 
    $verify = $_POST['verify_me'];  //Declaring variable to store verify

    //SQL query to update table user
    mysqli_query($conn, "UPDATE `tbluser` SET verify = $verify WHERE id = '$update_u_id'") or die('update query failed');

    //Redirect user to admin users
    header('location:admin_users.php');
}

if (isset($_POST['add_user'])) {
    $fisrtname = mysqli_real_escape_string($conn, $_POST['firstname']); //Declaring variable to store firstname
    $surname = mysqli_real_escape_string($conn, $_POST['surname']); //Declaring variable to store surname
    $studentnumber = mysqli_real_escape_string($conn, $_POST['studentNumber']); //Declaring variable to store student name
    $username = mysqli_real_escape_string($conn, $_POST['username']); //Declaring variable to store username
    $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); //Declaring variable to store password
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword'])); //Declaring variable to store confirm password
    $false = 'false'; //Declaring variable to store boolean value false

    //SQL query to select verify value from table user
    $select_users = mysqli_query($conn, "SELECT * FROM `tbluser` WHERE email = '$username' AND password = '$pass'") or die('select user in register query failed');

    //If select users query has contains rows greater than 0
    if (mysqli_num_rows($select_users) > 0) {
        //Displaying message to the user
        $message[] = 'user already exist!';
    } else {
        //if password does not match cornfirm password 
        if ($pass != $cpass) {
            //Displaying message to the user
            $message[] = 'confirm password not matched!';
        } else {
            //SQL query to insert values into user table
            mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password, verify) VALUES('$fisrtname', '$surname', '$studentnumber','$username','$cpass', $false)") or die('insert into register query failed');
            $message[] = 'user added successfully!';
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
    <!--font awesome style link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!--admin page style sheet-->
    <link rel="stylesheet" href="css/admin_style.css">

    <title>Users</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>

    <section class="users">

        <div class="box-container">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `tbluser`") or die('query failed');

            while ($fetch_user = mysqli_fetch_assoc($select_users)) {

            ?>

                <div class="box">
                    <p> username : <span><?php echo $fetch_user['firstname']; ?></span> </p>
                    <p> email : <span><?php echo $fetch_user['email']; ?></span> </p>
                    <p> verification : <span><?php echo $fetch_user['verify']; ?></span> </p>
                    <a href="admin_users.php?verify=<?php echo $fetch_user['id']; ?>" class="white-btn">verify</a>
                    <a href="admin_users.php?delete=<?php echo $fetch_user['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
                    <a href="admin_users.php?update=<?php echo $fetch_user['id']; ?>" class="option-btn">update</a>
                </div>
            <?php
            };
            ?>
        </div>

    </section>

    <!-- Update the user information -->
    <section class="edit-user-form">
        <?php
        if (isset($_GET['update'])) {
            $update_id  = $_GET['update'];

            $update_query = mysqli_query($conn, "SELECT * FROM `tbluser` WHERE id = '$update_id'") or die('query failed');

            if (mysqli_num_rows($update_query)) {
                while ($fetch_update = mysqli_fetch_assoc($update_query)) {


        ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_u_id" value="<?php echo $fetch_update['id']; ?>">
                        <input type="text" name="update_firstname" value="<?php echo $fetch_update['firstname']; ?>" placeholder="enter your first name" class="box" required>
                        <input type="text" name="update_surname" value="<?php echo $fetch_update['surname']; ?>" placeholder="enter your surname" class="box" required>
                        <input type="text" name="update_studentNumber" value="<?php echo $fetch_update['studentNumber']; ?>" placeholder="enter your student number" class="box" required>
                        <input type="text" name="update_username" value="<?php echo $fetch_update['email']; ?>" placeholder="enter your email" class="box" required>
                        <input type="password" name="update_password" value="<?php echo $fetch_update['password']; ?>" placeholder="enter your password" class="box" required>
                        <input type="password" name="update_cpassword" value="<?php echo $fetch_update['password']; ?>" placeholder="confirm your password" class="box" required>
                        <input type="submit" value="update" name="update_product" class="white-btn">
                        <input type="reset" value="cancel" id="close-update" class="option-btn">
                    </form>
        <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-user-form").style.display = "none";</script>';
        }
        ?>
    </section>
    <!-- Verify the user -->
    <section class="edit-user-form">
        <?php
        if (isset($_GET['verify'])) {
            $verify_id  = $_GET['verify'];

            $verify_query = mysqli_query($conn, "SELECT * FROM `tbluser` WHERE id = '$verify_id'") or die('query failed');

            if (mysqli_num_rows($verify_query)) {
                while ($fetch_verify = mysqli_fetch_assoc($verify_query)) {

        ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="verify_u_id" value="<?php echo $fetch_verify['id']; ?>">
                        <input type="text" name="verify_me" value="<?php echo $fetch_verify['verify']; ?>" placeholder="verify" class="box" required>
                        <input type="submit" value="verify" name="verify_user" class="btn">
                        <input type="reset" value="cancel" id="close-update" class="option-btn">
                    </form>
        <?php
                }
            }
        } else {
            echo '<script>document.querySelector(".edit-user-form").style.display = "none";</script>';
        }
        ?>
    </section>

    <section class="add-products">
        <h1 class="title">add user</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="firstname" placeholder="enter your first name" class="box" required>
            <input type="text" name="surname" placeholder="enter your surname" class="box" required>
            <input type="text" name="studentNumber" placeholder="enter your student number" class="box" required>
            <input type="text" name="username" placeholder="enter your email" class="box" required>
            <input type="password" name="password" placeholder="enter your password" class="box" required>
            <input type="password" name="cpassword" placeholder="confirm your password" class="box" required>
            <input type="submit" value="add user" name="add_user" class="btn">
        </form>
    </section>


    <!--Admin page js link-->
    <script src="js/admin_script.js"></script>

</body>

</html>