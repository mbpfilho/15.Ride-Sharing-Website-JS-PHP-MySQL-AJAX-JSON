<?php
// start session
session_start();
// connect to database
include("connection.php");

// check users inputs
//     define error messages
$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';
$errors="";

//     get email 
if(empty($_POST["forgotemail"])){
    $errors.=$missingEmail;
}else{
    $email=filter_var($_POST["forgotemail"],FILTER_SANITIZE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        //     store errors en errors variable
        $errors.=$invalidEmail;
    }
}

//     if there are, print errors
if($errors){
    $resultMessage="<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
    exit;
}

// no errors
//     prepare variables for queries
$email=mysqli_real_escape_string($link,$email);

//     run query: check if email exists in the users table
$sql="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
$count=mysqli_num_rows($result);
//     if email does not exist: print error
if($count!=1){
    echo "<div class='alert alert-danger'>Email not registered.</div>";
    exit;
}

//     else
//         get user_id
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
$user_id=$row["user_id"];

//         create unique activation code
$key=bin2hex(openssl_random_pseudo_bytes(16));

//         insert user details and activation code in the forgotpassword table
$time=time();
// $status="pending";
$sql="INSERT INTO forgotpassword (user_id,key1,time) VALUES ('$user_id','$key','$time')";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>Error inserting user details in the database.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}

//         send email with link to resetpassword.php with user id and activation code
$message="Please click on this link to reset your password:\n\n";
$message.="http://localhost/15.Ride-Sharing-Website-JS-PHP-MySQL-AJAX-JSON/resetpassword.php?user_id=$user_id&key=$key";
if(mail($email,"Reset your password",$message,"From:"."mabuened@gmail.com")){
    //         if email send succsseful
    //             print success message
    echo "<div class='alert alert-success'>An email has been sent to $email. Please click on the link to reset your password.</div>";
}else{
    echo "<div class='alert alert-danger'>Confirmation email failed.</div>";
}
?>