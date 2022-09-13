<?php
// <!-- start session -->
session_start();
// <!-- connect to database -->
include("connection.php");
// <!-- check users inputs -->
//     <!-- define error messages -->
$missingEmail = '<p><strong>Please enter your email address!</strong></p>';
$missingPassword = '<p><strong>Please enter your password!</strong></p>';
$errors="";
//     <!-- get email and password and store errors en errors varable -->
//get email
if(empty($_POST["loginemail"])){
    $errors.=$missingEmail;
}else{
    $email=filter_var($_POST["loginemail"],FILTER_SANITIZE_EMAIL);
}

//get password
if(empty($_POST["loginpassword"])){
    $errors.=$missingPassword;
}else{
    $password=htmlspecialchars($_POST["loginpassword"]);
}

//     <!-- if there are, print errors -->
if($errors){
    $resultMessage="<div class='alert alert-danger'>$errors</div>";
    echo $resultMessage;
    exit;
}
// <!-- no errors -->
//     <!-- prepare variables dor queries -->
$email=mysqli_real_escape_string($link,$email);
$password=mysqli_real_escape_string($link,$password);
// $password=md5($password); //128 bits -> 32 characters
$password=hash("sha256",$password); //256 bits -> 64 characters

//     <!-- run query: check combination email and password -->
$sql="SELECT * FROM users WHERE email='$email' AND password='$password' AND activation=TRUE";
$result=mysqli_query($link,$sql);
//check if query ran successfully
if(!$result){
    echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
    exit;
}
//check if there is only one match
$count=mysqli_num_rows($result); //check how many results match
//     <!-- if email and password dont match, print error -->
if($count !== 1){
    echo "<div class='alert alert-danger'>Wrong username or password.</div>";
    // echo "<p>$count</p>";
    // echo mysqli_num_rows($result);
    // echo "<p>$password</p>";
    exit;
}
//     <!-- else -->
//         <!-- log user in: set session variables -->
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
$_SESSION["user_id"]=$row["user_id"];
$_SESSION["username"]=$row["username"];
$_SESSION["email"]=$row["email"];
$_SESSION["picture"]=$row["profilepicture"];

//         <!-- check if rememberme is not checked -->
if(empty($_POST["rememberme"])){
    //             <!-- print success -->
    echo "success";
    exit;
}

//         <!-- else -->
//             <!-- create two variables $authentications1 and authentication2 -->
$authentificator1=bin2hex(openssl_random_pseudo_bytes(10));
$authentificator2=openssl_random_pseudo_bytes(20);
//             <!-- store them in a cookie -->
function f1($a,$b){
    $c=$a.",".bin2hex($b);
    return $c;
}
$cookieValue=f1($authentificator1,$authentificator2);
setcookie(
    "rememberme",
    $cookieValue,
    time()+15*24*60*60
);
function f2($a){
    $b=hash("sha256",$a);
    return $b;
}
$f2authentificator2=f2($authentificator2);
$user_id=$_SESSION["user_id"];
$expiration=date("Y-m-d H:i:s",time()+15*24*60*60);
// runquery to store them in rememberme table
$sql="INSERT INTO rememberme (authentificator1,f2authentificator2,user_id,expires) VALUES ('$authentificator1','$f2authentificator2','$user_id','$expiration')"; 
$result=mysqli_query($link,$sql);
//             <!-- run query unsuccessful -->
if(!$result){
    //                 <!-- print error -->
    echo "<div class='alert alert-danger'><p>Error storing rememberme data:</p><p>".mysqli_error($link)."</p></div>";
    // echo '<script>alert("Error storing rememberme data.")</script>';
    exit;
}

//run a query to delete the empty notes
$sql="DELETE FROM rememberme WHERE expires<'$expiration'";
$result=mysqli_query($link,$sql);
if (!$result){
    echo "<div class='alert alert-warning'>An error occured deleting old rememberme keys.</div>";
    exit;
}

//preparing $_SESSION["id"] from data extracted from table
$sql="SELECT * FROM rememberme WHERE authentificator1='$authentificator1' AND f2authentificator2='$f2authentificator2' AND user_id='$user_id'";
$result=mysqli_query($link,$sql);
if (!$result){
    echo "<div class='alert alert-warning'>An error occured preparing rememberme id for the session.</div>";
    exit;
}
$row=mysqli_fetch_array($result,MYSQLI_ASSOC); 
$id=$row["id"];
$_SESSION["id"]=$id;
//   else print success
echo "success";

?>