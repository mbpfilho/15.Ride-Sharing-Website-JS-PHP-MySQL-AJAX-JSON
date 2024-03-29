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
    $_SESSION["picture"]=$row["profilepicture"];
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

    <title>My Trips</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo&family=Open+Sans&family=Source+Sans+Pro&family=Vollkorn&display=swap" rel="stylesheet">
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

    <!-- jquery ui -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/sunny/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <!-- css file -->
    <link href="styling.css" rel="stylesheet">

    <!-- google maps api script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZr-cTvyLpSsWL7Q9Nlc-RTp1WTDkmJcs&libraries=places"></script>

    <style>
        #container{
            margin-top: 120px;
        }

        #googleMap{
            max-width: 400px;
            height: 200px;
            margin: 30px auto;
        }
        .modal{
            z-index: 20;
            margin-top: 36px;
        }
        .modal-backdrop{
            z-index: 10;
        }

        .time{
            margin-top: 10px;
        }

        .trip{
            margin: 10px auto;
            border: 1px solid gray;
            border-radius: 10px;
            padding: 6px;
            background: linear-gradient(lightgray, white);
        }

        .departure, .destination, .seatsAvailable{
            font-size: 1.2em;
        }

        .price{
            font-size: 1.6em;
        }

        #myTrips{
            margin-top: 20px;
            margin-bottom: 100px;
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
                    <li><a href="#"><div data-toggle="modal" data-target="#updatepicture">
                        <?php
                        if(empty($picture)){
                            echo "<img class='preview' src='profilepicture/cn.jpg' alt='profile picture'>";
                        }else{
                            echo "<img class='preview' src='$picture' alt='profile picture'>";
                        }
                        ?>  
                    </div></a></li>
                    <li><a href="#"><?php echo $username;?></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- container -->
    <div class="container" id="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div>
                    <button type="button" class="btn btn-lg blue" data-toggle="modal" data-target="#addtripModal">
                        Add trips
                    </button>
                </div>
                <div id="myTrips" class="trips">
                <!-- ajax call to php file -->
                </div>
            </div>
        </div>
    </div>


    <!-- add trip form -->
    <form method="post" id="addtripForm">
        <div class="modal" id="addtripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">New trip:</h4>
                </div>
                <div class="modal-body">
                    <!-- add trip message from php file -->
                    <div id="addtripmessage"></div>
                    
                    <!-- google map -->
                    <div id="googleMap"></div>

                    <div class="form-group">
                        <label for="departure" class="sr-only">Departure</label>
                        <input class="form-control" id="departure" type="text" name="departure" placeholder="Departure">
                    </div>
                    
                    <div class="form-group">
                        <label for="destination" class="sr-only">Destination</label>
                        <input class="form-control" id="destination" type="text" name="destination" placeholder="Destination">
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="sr-only">Price</label>
                        <input class="form-control" id="price" type="number" name="price" placeholder="Price">
                    </div>
                    
                    <div class="form-group">
                        <label for="seatsavailable" class="sr-only">Seats Available</label>
                        <input class="form-control" id="seatsavailable" type="number" name="seatsavailable" placeholder="Seats Available">
                    </div>

                    <div class="form-group">
                        <label for=""><input type="radio" name="regular" id="yes" value="Y">Regular</label>
                        <label for=""><input type="radio" name="regular" id="no" value="N">One-off</label>
                    </div>

                    <div class="checkbox checkbox-inline regular">
                        <label for=""><input type="checkbox" name="sunday" id="sunday" value="1">Sunday . </label>
                        <label for=""><input type="checkbox" name="monday" id="monday" value="1">Monday . </label>
                        <label for=""><input type="checkbox" name="tuesday" id="tuesday" value="1">Tuesday . </label>
                        <label for=""><input type="checkbox" name="wednesday" id="wednesday" value="1">Wednesday . </label>
                        <label for=""><input type="checkbox" name="thursday" id="thursday" value="1">Thursday . </label>
                        <label for=""><input type="checkbox" name="friday" id="friday" value="1">Friday . </label>
                        <label for=""><input type="checkbox" name="saturday" id="saturday" value="1">Saturday</label>
                    </div>
                    
                    <div class="form-group one-off">
                        <label for="date" class="sr-only">Date</label>
                        <input class="form-control" id="date" name="date" readonly="readonly" placeholder="Date">
                    </div>
                    
                    <div class="form-group regular one-off time">
                        <label for="time" class="sr-only">Time</label>
                        <input class="form-control" id="time" type="time" name="time">
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" name="createTrip" type="submit" value="Create Trip">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <!-- edit trip form -->
    <form method="post" id="edittripForm">
        <div class="modal" id="edittripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Edit trip:</h4>
                </div>
                <div class="modal-body">

                    <!-- edit trip message from php file -->
                    <div id="edittripmessage"></div>

                    <div class="form-group">
                        <label for="departure2" class="sr-only">Departure</label>
                        <input class="form-control" id="departure2" type="text" name="departure2" placeholder="Departure">
                    </div>
                    
                    <div class="form-group">
                        <label for="destination2" class="sr-only">Destination</label>
                        <input class="form-control" id="destination2" type="text" name="destination2" placeholder="Destination">
                    </div>
                    
                    <div class="form-group">
                        <label for="price2" class="sr-only">Price</label>
                        <input class="form-control" id="price2" type="number" name="price2" placeholder="Price">
                    </div>
                    
                    <div class="form-group">
                        <label for="seatsavailable2" class="sr-only">Seats Available</label>
                        <input class="form-control" id="seatsavailable2" type="number" name="seatsavailable2" placeholder="Seats Available">
                    </div>

                    <div class="form-group">
                        <label for=""><input type="radio" name="regular2" id="yes2" value="Y">Regular</label>
                        <label for=""><input type="radio" name="regular2" id="no2" value="N">One-off</label>
                    </div>

                    <div class="checkbox checkbox-inline regular2">
                        <label for=""><input type="checkbox" name="sunday2" id="sunday2" value="1">Sunday . </label>
                        <label for=""><input type="checkbox" name="monday2" id="monday2" value="1">Monday . </label>
                        <label for=""><input type="checkbox" name="tuesday2" id="tuesday2" value="1">Tuesday . </label>
                        <label for=""><input type="checkbox" name="wednesday2" id="wednesday2" value="1">Wednesday . </label>
                        <label for=""><input type="checkbox" name="thursday2" id="thursday2" value="1">Thursday . </label>
                        <label for=""><input type="checkbox" name="friday2" id="friday2" value="1">Friday . </label>
                        <label for=""><input type="checkbox" name="saturday2" id="saturday2" value="1">Saturday</label>
                    </div>
                    
                    <div class="form-group one-off2">
                        <label for="date2" class="sr-only">Date</label>
                        <input class="form-control" id="date2" name="date2" readonly="readonly" placeholder="Date">
                    </div>
                    
                    <div class="form-group regular2 one-off2 time">
                        <label for="time2" class="sr-only">Time</label>
                        <input class="form-control" id="time2" type="time" name="time2">
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn btn-primary" name="updateTrip" type="submit" value="Edit Trip">
                    <input class="btn btn-danger" name="deleteTrip" type="button" id="deleteTrip" value="Delete">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <!-- Footer -->
    <div id="footer">
        <div class="container">
            <p>Copyright &copy; 2022-<?php echo date("Y")?></p>
        </div>
    </div>

    <!-- spinner -->
    <div id="spinner">
        <img src="Disk.gif" alt="spinner" width="64" height="64">
        <br>Loading..
    </div>

    <!-- link to mynotes.js -->
    <script src="mytrips.js"></script>
    <script src="map.js"></script>
  </body>
</html>