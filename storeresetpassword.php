<?php
// this file receives the iser_id, generated key to reset password, password1 and password2

// this file then resets password is all checks are correct

// two POST parameters: iser_id, generated key 
session_start();
include("connection.php");
// user_id or key1 missing 
if(!isset($_POST["user_id"])||!isset($_POST["key"])){
    // error message 
    echo "<div class='alert alert-danger'>There was an error. Please click on the link on the email.</div>";
    exit;
}
// else
//     store them in two variables
$user_id=$_POST["user_id"];
$key=$_POST["key"];

// define a time variable: now minus 24 hours
$time=time()-86400;

//     prepare varable for query
$user_id=mysqli_real_escape_string($link,$user_id);
$key=mysqli_real_escape_string($link,$key);

// run query: check combination of user_id and key exists ans is less than 24 hours old 
$sql="SELECT user_id FROM forgotpassword WHERE key1='$key' AND user_id='$user_id' and time >'$time'";
$result=mysqli_query($link,$sql);

// if query fails show error message
if(!$result){
    echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
// if combination does not exist 
// print error message 
$count=mysqli_num_rows($result); 
if($count !== 1){
    echo "<div class='alert alert-danger'>Please try again.</div>";
    exit;
}


//     define error messages
$missingPassword = '<p><strong>Please enter a Password!</strong></p>';
$invalidPassword = '<p><strong>Your password should be at least 6 characters long and include one capital letter, one lowercase letter and one number!</strong></p>';
$differentPassword = '<p><strong>Passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm your password</strong></p>';
$errors="";

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
$password=mysqli_real_escape_string($link,$password);
// $password=md5($password); //128 bits -> 32 characters
$password=hash("sha256",$password); //256 bits -> 64 characters
$user_id=mysqli_real_escape_string($link,$user_id);

//run query to update user password in the users table
$sql="UPDATE users SET password='$password' WHERE user_id='$user_id'";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>There was a problem storing the new password in the database.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}

//run query to delete data from de forgotpassword table
$sql="DELETE FROM forgotpassword WHERE user_id='$user_id'";
$result=mysqli_query($link,$sql);
if(!$result){
    echo "<div class='alert alert-danger'><p>There was a problem deleting the new password key in the database.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
echo "<div class='alert alert-success'><p>Password updated.</p><a href='index.php'>Log in</a></div>";
?>