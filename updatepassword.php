<?php
//start session and connect
session_start();
include ("connection.php");

//define error messages
$missingCurrentPassword="<p><strong>Please enter current password.</strong></p>";
$incorrectCurrentPassword="<p><strong>Wrong password.</strong></p>";
$missingPassword = '<p><strong>Please enter a new password!</strong></p>';
$invalidPassword = '<p><strong>The new password should be at least 6 characters long and include one capital letter, one lowercase letter and one number!</strong></p>';
$differentPassword = '<p><strong>New passwords don\'t match!</strong></p>';
$missingPassword2 = '<p><strong>Please confirm the new password</strong></p>';
$errors="";

//check for errors
if(empty($_POST["currentpassword"])){
    $errors.=$missingCurrentPassword;
}else{
    // $currentPassword=$_POST["currentpassword"];
    $currentPassword=htmlspecialchars($_POST["currentpassword"]);
    $currentPassword=mysqli_real_escape_string($link,$currentPassword);
    $currentPassword=hash("sha256",$currentPassword);
    //check if password is correct
    $user_id=$_SESSION["user_id"];
    $sql="SELECT password FROM users WHERE user_id='$user_id'";
    $result=mysqli_query($link,$sql);
    $count=mysqli_num_rows($result);
    if($count!==1){
        echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    }else{
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($currentPassword!=$row["password"]){
            $errors.=$incorrectCurrentPassword;
        }
    }
}
//get and test new passwords
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

//if there is error, print message
if($errors){
    $resultMessage="<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
}else{
    //prepare password variable
    $password=mysqli_real_escape_string($link,$password);
    $password=hash("sha256",$password);
    
    //else run query and update password
    $sql="UPDATE users SET password='$password' WHERE user_id='$user_id'";
    $result=mysqli_query($link,$sql);
    if(!$result){
        echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
        exit;
    }else{
        echo "<div class='alert alert-success'><p>Password updated.</p></div>";
    }
}



?>