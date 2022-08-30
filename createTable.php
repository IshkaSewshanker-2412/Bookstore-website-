<?php
//Embedding DBConn.php
include 'DBConn.php';

//If submit button is clicked
if (isset($_POST['submit'])) {
    $fisrtname = mysqli_real_escape_string($conn, $_POST['firstname']); //Declaring variable to store firstname
    $surname = mysqli_real_escape_string($conn, $_POST['surname']); //Declaring variable to store surname
    $studentnumber = mysqli_real_escape_string($conn, $_POST['studentNumber']); //Declaring variable to store studentNumber
    $username = mysqli_real_escape_string($conn, $_POST['username']); //Declaring variable to store username
    $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); //Declaring variable to store password
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword'])); //Declaring variable to store confirm password

    //SQL Query to select users
    $select_users = mysqli_query($conn, "SELECT * FROM `tbluser`") or die('select user table query failed');

    //If select users query has contains rows greater than 0
    if (mysqli_num_rows($select_users) > 0) {
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

        //Declaring variable to store userData file
        $myfile = fopen("userData.txt", "r") or die("File cannot be opened!");

        //While loop to loop through the userData.txt file
        while (!feof($myfile)) {
            $filecontent = fgets($myfile); //Declaring variable to store file content
            $array = explode("/", $filecontent); //Declaring array
            list($name, $surName, $userName, $stNum, $password, $verify) = $array; //Assigning values to array 
            $hashedpass = md5($password); //Hashing the password

            //SQL Query to insert values from userData.txt
            $qryInsert = "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password, verify) VALUES('$name', '$surName', '$stNum','$userName','$hashedpass', $verify)";
            $conn->query($qryInsert);
            var_dump($password);
        }
        //Closing file
        fclose($myfile);



        /*mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password) VALUES('David', 'Silva', 'ST12107470','davidsilva@gmail.com','81dc9bdb52d04dc20036dbd83')") or die('query failed');
        mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password) VALUES('Kevin', 'De Bruyne', 'ST36987452','kevindebruyne@gmail.com','e82c4b19b8151ddc25d4d93baf7b908f')") or die('query failed');
        mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password) VALUES('Ruben', 'Dias', 'ST15263794','rubendias@gmail.com','0f3839dc22ff5ad19a6b74203fac591f')") or die('query failed');
        mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password) VALUES('Phil', 'Foden', 'ST25869874','philfoden@gmail.com','7c022d9c6dd601e087e186ee6dfe1fc7')") or die('query failed');
        mysqli_query($conn, "INSERT INTO `tbluser`(firstname, surname, studentNumber, email, password) VALUES('Raheem', 'Sterling', 'ST98745612','raheemsterling@gmail.com','d93591bdf7860e1e4ee2fca799911215')") or die('query failed');

        $message[] = 'table exist!';*/
    }
}
