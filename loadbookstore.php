<?php

include 'DBConn.php';

//SQL query to delete table  user
mysqli_query($conn, "DROP TABLE `tbluser`") or die('delte user table query failed');
//SQL query to create table user
mysqli_query($conn, "CREATE TABLE `tbluser`
(
id int AUTO_INCREMENT Primary key,
firstname VARCHAR(255),
surname VARCHAR(255),
studentNumber VARCHAR(255),
email VARCHAR(255),
password VARCHAR(255),
verify BOOLEAN
)") or die('insert into table query failed');

//SQL query to delete table  admin
mysqli_query($conn, "DROP TABLE `tbladmin`") or die('delte user table query failed');
//SQL query to create table admin
mysqli_query($conn, "CREATE TABLE `tbladmin`
(
id int AUTO_INCREMENT Primary key,
firstname VARCHAR(255),
surname VARCHAR(255),
studentNumber VARCHAR(255),
email VARCHAR(255),
password VARCHAR(255)
)") or die('insert into table query failed');

//SQL query to delete table  order
mysqli_query($conn, "DROP TABLE `tblorder`") or die('delte user table query failed');
//SQL query to create table order
mysqli_query($conn, "CREATE TABLE `tblorder`
(
id int AUTO_INCREMENT Primary key
)") or die('insert into table query failed');

//SQL query to delete table  books
mysqli_query($conn, "DROP TABLE `tblbooks`") or die('delte user table query failed');
//SQL query to create table books
mysqli_query($conn, "CREATE TABLE `tblbooks`
(
Book_id int AUTO_INCREMENT Primary key,
Name VARCHAR(255),
Price VARCHAR(255),
Quantity VARCHAR(255)
)") or die('insert into table query failed');
