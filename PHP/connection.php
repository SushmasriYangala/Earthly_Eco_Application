<?php
//STEP 1: setup our datbaase 
$servername = 'localhost';
$username = 'yangal56';
$password = '1PInk6kItten!';
$databsename = 'yangal56';

//STEP 2: create a connection to our databse using MySQLi
$conn = mysqli_connect($servername,$username,$password,$databsename);
if(!$conn)
{
    die('connection unsuccessfull:'.mysqli_connect_error());
} 
?>
