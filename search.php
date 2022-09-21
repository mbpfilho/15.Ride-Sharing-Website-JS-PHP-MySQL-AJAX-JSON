<?php
session_start();
include("connection.php");

//define error messages
$missingDeparture="<p><strong>Enter a departure</strong></p>";
$invalidDeparture="<p><strong>Enter a valid departure</strong></p>";
$missingDestination="<p><strong>Enter a destination</strong></p>";
$invalidDestination="<p><strong>Enter a valid destination</strong></p>";
$errors="";

//get inputs
$departure=$_POST["departure"];
$destination=$_POST["destination"];

//check departure
if(empty($departure)){
    $errors.=$missingDeparture;
}else{
    //check coordinates
    if(!isset($_POST["departureLatitude"])or!isset($_POST["departureLongitude"])){
        $errors.=$invalidDeparture;
    }else{
        $departureLatitude=$_POST["departureLatitude"];
        $departureLongitude=$_POST["departureLongitude"];
        $departure=htmlspecialchars($departure);
    }
}

//check destination
if(empty($destination)){
    $errors.=$missingDestination;
}else{
    //check coordinates
    if(!isset($_POST["destinationLatitude"])or!isset($_POST["destinationLongitude"])){
        $errors.=$invalidDestination;
    }else{
        $destinationLatitude=$_POST["destinationLatitude"];
        $destinationLongitude=$_POST["destinationLongitude"];
        $destination=htmlspecialchars($destination);
    }
}

//check if there is an error and print an error message
if($errors){
    echo "<div class='alert alert-danger'>$errors</div>";
    exit;
}

?>