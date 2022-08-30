<?php
//Embedding DBConn.php
include 'DBConn.php';

//Starting session
session_start();

//Declaring variable to store admin_id from session
$admin_id = $_SESSION['admin_id'];

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

    <title>Admin Page</title>
</head>

<body>
    <?php
    //Emdedding admin_header.php
    include 'admin_header.php'; ?>

    <section class="dashboard">
        <h1 class="heading">dashboard</h1>

        <div class="box-container">
            <?php

            ?>
        </div>
    </section>

    <!--Admin page js link-->
    <script src="js/admin_script.js"></script>

</body>

</html>