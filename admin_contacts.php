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

    //SQL query to delete record on the tblorder table
    mysqli_query($conn, "DELETE FROM `tblmessages` WHERE id = '$delete_id'") or die('query failed');
    //Redirect user to the admin contacts page
    header('location:admin_contacts.php');
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

    <title>Contacts</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>


    <section class="messages">

        <h1 class="title"> messages </h1>

        <div class="box-container">
            <?php
            $select_message = mysqli_query($conn, "SELECT * FROM `tblmessages`") or die('query failed');
            if (mysqli_num_rows($select_message) > 0) {
                while ($fetch_message = mysqli_fetch_assoc($select_message)) {

            ?>
                    <div class="box">
                        <p> user id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
                        <p> name : <span><?php echo $fetch_message['name']; ?></span> </p>
                        <p> number : <span><?php echo $fetch_message['number']; ?></span> </p>
                        <p> email : <span><?php echo $fetch_message['email']; ?></span> </p>
                        <p> message : <span><?php echo $fetch_message['message']; ?></span> </p>
                        <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete message</a>
                    </div>
            <?php
                };
            } else {
                echo '<p class="empty">you have no messages!</p>';
            }
            ?>
        </div>

    </section>




    <!--Admin page js link-->
    <script src="js/admin_script.js"></script>

</body>

</html>