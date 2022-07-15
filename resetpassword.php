<?php
// this file receives the key to freate a new password

// this file display a form to input new password 

// RESETPASSWORD link contains two GET parameters: user_id and  key
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
            <title>Password Reset</title>

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
                        <h1>Reset Password</h1>
                        <div id="resultmessage"></div>
<?php 
// user_id or key1 missing 
if(!isset($_GET["user_id"])||!isset($_GET["key"])){
    // error message 
    echo "<div class='alert alert-danger'>There was an error. Please click on the link on the email.</div>";
    exit;
}
// else
//     store them in two variables
$user_id=$_GET["user_id"];
$key=$_GET["key"];

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
// else
// print reset password form with hidden user_id and key fields 
echo "
<form method='post' id='passwordreset'>
    <input type='hidden' name='key' value='$key'>
    <input type='hidden' name='user_id' value='$user_id'>
    <div class='form-group'>
        <label for='password'>Enter your new password</label>
        <input type='password' name='password' id='password' placeholder='Enter Password' class='form-control'>
    <div class='form-group'>
        <label for='password2'>Re-enter Password</label>
        <input type='password' name='password2' id='password2' placeholder='Re-enter Password' class='form-control'>
        <input type='submit' name='resetpassword' class='btn btn-success btn-lg' value='Reset Password'>
    </div>
</form>
";
?>
                    </div>
                </div>
            </div>
            <!-- script for ajax call to storeresetpassword.php which processes form data  -->
            <script>                
                //form submited
                $("#passwordreset").submit(function(event){    
                    //prevent default php processing
                    event.preventDefault();
                    //collect users inputs
                    var datatopost=$(this).serializeArray();
                    //send them to forgot-password.php using ajax
                    $.ajax({
                        url:"storeresetpassword.php",
                        type:"POST",
                        data: datatopost,
                        success:function(data){
                            $("#resultmessage").html(data);
                        },
                        error: function(){
                            //ajax call fails: show ajax call error
                            $("#resultmessage").html("<div class='alert alert-danger'>Ajax call error</div>");
                        }
                    });
                });
            </script>
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        </body>