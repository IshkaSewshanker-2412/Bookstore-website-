<?php
//Embedding DBConn.php
include 'DBConn.php';

//Starting session
session_start();
//unsetting session
session_unset();
//destroying session
session_destroy();

//Redirecting user to login 
header('location:adminlogin.php');
