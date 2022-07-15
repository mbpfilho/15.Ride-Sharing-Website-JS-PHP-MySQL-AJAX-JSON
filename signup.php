<?php
//start session
session_start();
// connect to database
include("connection.php");
// check users inputs
//     define error messages
$missingUsername = '<p><strong>Please enter a username!</strong></p>';
$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$invalidEmail = '<p><strong>Please enter a valid email address!</strong></p>';
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include one capital letter, one lowercase letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password</strong></p>';
$errors="";
//     get username email password password2     store errors en errors varable
//get username
if(empty($_POST["username"])){
    $errors.=$missingUsername;
}else{
    $username=htmlspecialchars($_POST["username"]);
}

//get email
if(empty($_POST["email"])){
    $errors.=$missingEmail;
}else{
    $email=filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors.=$invalidEmail;
    }
}

//get passwords
if(empty($_POST["password"])){
    $errors.=$missingPassword;
}elseif(!(strlen($_POST["password"])>5 and preg_match("/[A-Z]/",$_POST["password"]) and preg_match("/[a-z]/",$_POST["password"]) and preg_match("/[0-9]/",$_POST["password"]))){
    $errors.=$invalidPassword;
}else{
    $password=htmlspecialchars($_POST["password"]);
    if(empty($_POST["password2"])){
        $errors.=$missingPassword2;
    }else{
        $password2=htmlspecialchars($_POST["password2"]);
        if($password!==$password2){
            $errors.=$differentPassword;
        }
    }
}
//     if ther are, print errors
if($errors){
    $resultMessage="<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
    exit;
}
// no errors
//     prepare variables for queries
$username=mysqli_real_escape_string($link,$username);
$email=mysqli_real_escape_string($link,$email);
$password=mysqli_real_escape_string($link,$password);
// $password=md5($password); //128 bits -> 32 characters
$password=hash("sha256",$password); //256 bits -> 64 characters

//     if username exists in users table, print error
$sql="SELECT * FROM users WHERE username='$username'";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
$results=mysqli_num_rows($result);
if($results){
    echo "<div class='alert alert-danger'>Username already registered.</div>";
    exit;
}

//         if email exists in users table, print error
$sql="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
$results=mysqli_num_rows($result);
if($results){
    echo "<div class='alert alert-danger'>Email already registered.</div>";
    exit;
}

//             create unique activation code
$activationKey=bin2hex(openssl_random_pseudo_bytes(16));

//             insert user details and activation code in users table
$sql="INSERT INTO users (username,email,password,activation,activationkey) VALUES ('$username','$email','$password',FALSE,'$activationKey')";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>Error inserting user details in the database.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
//             send user email with link do activate.php with email and actovation code
$message="Please click on this link to activate your account:\n\n";
$message.="http://localhost/15.Ride-Sharing-Website-JS-PHP-MySQL-AJAX-JSON/activate.php?email=".urlencode($email)."&key=$activationKey";
if(mail($email,"Confirm your registration",$message,"From:"."mabuened@gmail.com")){
    echo "<div class='alert alert-success'>Thank you for registring! A confirmation email has been sent to $email. Please click on the activation link to activate your account.</div>";
}else{
    echo "<div class='alert alert-danger'>Confirmation email failed.</div>";
}
?>