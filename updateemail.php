<?php
//start session and connect
session_start();
include("connection.php");

//get user_id and new email sent through Ajax
$user_id=$_SESSION["user_id"];
$newemail=$_POST["email"];

//check if the new email exists on database
$sql="SELECT * FROM users WHERE email='$newemail'";
$result=mysqli_query($link,$sql);
$count=mysqli_num_rows($result);
if($count>0){
    echo "<div class='alert alert-danger'><p>Email already registred. Choose another one.</p></div>";
    exit;
}

//get the current email
$sql="SELECT * FROM users WHERE user_id='$user_id'";
$result=mysqli_query($link,$sql);
//store number of rows
$count=mysqli_num_rows($result);

if($count==1){
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    $email=$row["email"];
}else{
    echo"<div class='alert alert-danger'>Error retrieving the email from the database</div>";
    exit;
}

//create a unique activation code
$activationKey=bin2hex(openssl_random_pseudo_bytes(16));

//insert the new activation code in the users table
$sql="UPDATE users SET activationkey='$activationKey' WHERE user_id='$user_id'"; //***************************** */
$result=mysqli_query($link,$sql);

if(!$result){
    echo "<div class='alert alert-danger'>Error inserting new email in the database</div>";
}else{
    //send email with link to activatenewemail.php with corrent email, new email and activation code
    $message="Please click on this link to update email:\n\n";
    $message.="http://localhost/15.Ride-Sharing-Website-JS-PHP-MySQL-AJAX-JSON/activatenewemail.php?email=".urlencode($email)."&newemail=".urlencode($newemail)."&key=$activationKey";
    if(mail($newemail,"Confirm email update",$message,"From:"."mabuened@gmail.com")){
        echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the activation link to update your email.</div>";
    }else{
        echo "<div class='alert alert-danger'>Confirmation email failed.</div>";
    }
}




?>