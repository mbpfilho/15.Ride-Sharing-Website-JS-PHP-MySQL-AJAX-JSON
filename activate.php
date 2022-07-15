<?php
// user is redirect to this file after clicking the activation link 
// signup link contains two GET parameters: email and activation key
session_start();
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
            <title>Account Activation</title>

            <!-- Bootstrap -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

            <style>
                h1{
                    color:purple;
                }
                h3{
                    color:lightgreen;
                }
                .contactForm{
                    border:1px solid purple;
                    margin-top:50px;
                    border-radius:15px;
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="offset-sm-1 col-sm-10 contactForm">
                        <h1>Account Activation</h1>
<?php
// if email or activation key is missing show an error
if(!isset($_GET["email"])||!isset($_GET["key"])){
    echo "<div class='alert alert-danger'>There was an error. Please click on the activation link from the email.</div>";
    exit;
}
// else
//     store them in two variables
$email=$_GET["email"];
$key=$_GET["key"];
//     prepare varable for query
$email=mysqli_real_escape_string($link,$email);
$key=mysqli_real_escape_string($link,$key);
//     run query: set activation field do activated for provided email
$sql="UPDATE users SET activation=TRUE,activationkey='0' WHERE (email='$email' AND activationkey='$key') LIMIT 1";
$result=mysqli_query($link,$sql);
//     if query successful, show message and invite to login
if(mysqli_affected_rows($link)==1){
    echo "<div class='alert alert-success'>Your account has been activated.</div>";
    echo "<a href='index.php' type='button' class='btn-lg btn-success'>Log in</a>";
}else{
    //     else
    echo "<div class='alert alert-danger'>Your account could not be activated.</div>";
}
    //     show error message
?>
                       
                    </div>
                </div>
            </div>
<?php

?>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        </body>
</html>