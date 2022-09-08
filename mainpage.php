<?php
session_start();
if(!isset($_SESSION["user_id"])){
    header("location:index.php");
}
include("connection.php");
$user_id=$_SESSION["user_id"];

//get username and email
$sql="SELECT * FROM users WHERE user_id='$user_id'";
$result=mysqli_query($link,$sql);
//store number of rows
$count=mysqli_num_rows($result);

if($count==1){
    // $row=mysqli_fetch_assoc($result);?????
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    $username=$row["username"];
    $email=$row["email"];
    $_SESSION["username"]=$username;
    $_SESSION["email"]=$email;
}else{
    echo"Error retrieving username and email from the database";
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- favicon links -->
    <link rel="apple-touch-icon" sizes="180x180" href="fonts/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="fonts/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="fonts/favicon-16x16.png">
    <link rel="manifest" href="fonts/site.webmanifest">

    <title>My Notes</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo&family=Open+Sans&family=Source+Sans+Pro&family=Vollkorn&display=swap" rel="stylesheet">

    <link href="styling.css" rel="stylesheet">

    <style>
        #container{
            margin-top: 120px;
        }

        #notePad,#allNotes,#done,.delete{
            display: none;
        }

        #buttons{
            margin-bottom: 20px;
        }

        textarea{
            width: 100%;
            max-width: 100%;
            font-size: 16px;
            line-height: 1.5em;
            border-left-width: 20px;
            border-color: #ca3dd9;
            color: #ca3dd9;
            background-color: #fbefff;
            padding: 10px;
        }

        .noteheader{
            border: 1px solid grey;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 0 10px;
            background: linear-gradient(#ECEAE7,#FFFFFF);
        }

        .text {
            font-size: 20px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .timetext {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header"> 
                <a class="navbar-brand">Ride Sharing</a>
                <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Search</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li class="active"><a href="#">My Trips</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><?php echo $_SESSION["username"];?></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- container -->
    <div class="container" id="container">
        <div class="row">
            
        </div>
    </div>

    <!-- Footer -->
    <div id="footer">
        <div class="container">
            <p>Copyright &copy; 2022-<?php echo date("Y")?></p>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <!-- link to mynotes.js -->
    <script src="mytrips.js"></script>
  </body>
</html>