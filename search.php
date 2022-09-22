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

//set search radius
$searchRaduis=10;

//longitude out of range
$departureLngOutOfRange=false;
$destinationLngOutOfRange=false;

//min max departure longitude
$deltaLongitudeDeparture=$searchRaduis*360/24901*cos(deg2rad($departureLatitude));
$minLongitudeDeparture=$departureLongitude-$deltaLongitudeDeparture;
if($minLongitudeDeparture<-180){
    $minLongitudeDeparture+=360;
    $departureLngOutOfRange=true;
}
$maxLongitudeDeparture=$departureLongitude+$deltaLongitudeDeparture;
if($maxLongitudeDeparture>180){
    $maxLongitudeDeparture-=360;
    $departureLngOutOfRange=true;
}

//min max destination longitude
$deltaLongitudeDestination=$searchRaduis*360/24901*cos(deg2rad($destinationLatitude));
$minLongitudeDestination=$destinationLongitude-$deltaLongitudeDestination;
if($minLongitudeDestination<-180){
    $minLongitudeDestination+=360;
    $destinationLngOutOfRange=true;
}
$maxLongitudeDestination=$destinationLongitude+$deltaLongitudeDestination;
if($maxLongitudeDestination>180){
    $maxLongitudeDestination-=360;
    $destinationLngOutOfRange=true;
}

//min max departure latitude
$deltaLatitudeDeparture=$searchRaduis*180/12430;
$minLatitudeDeparture=$departureLatitude-$deltaLatitudeDeparture;
if($minLatitudeDeparture<-90){$minLatitudeDeparture=-90;}
$maxLatitudeDeparture=$departureLatitude+$deltaLatitudeDeparture;
if($maxLatitudeDeparture>90){$maxLatitudeDeparture=90;}

//min max destination latitude
$deltaLatitudeDestination=$searchRaduis*180/12430;
$minLatitudeDestination=$destinationLatitude-$deltaLatitudeDestination;
if($minLatitudeDestination<-90){$minLatitudeDestination=-90;}
$maxLatitudeDestination=$destinationLatitude+$deltaLatitudeDestination;
if($maxLatitudeDestination>90){$maxLatitudeDestination=90;}

//build query
$sql="SELECT * FROM carsharetrips WHERE";

//departure longitude
if($departureLngOutOfRange){
    $sql.="((departureLongitude > $minLongitudeDeparture) OR (departureLongitude > $maxLongitudeDeparture))";
}else{
    $sql.="(departureLongitude BETWEEN $minLongitudeDeparture AND $maxLongitudeDeparture)";
}

//departure Latitude
$sql.=" AND (departureLatitude BETWEEN $minLatitudeDeparture AND $maxLatitudeDeparture)";

//destination longitude
if($destinationLngOutOfRange){
    $sql.=" AND ((destinationLongitude > $minLongitudeDestination) OR (destinationLongitude > $maxLongitudeDestination))";
}else{
    $sql.=" AND (destinationLongitude BETWEEN $minLongitudeDestination AND $maxLongitudeDestination)";
}

//destination Latitude
$sql.=" AND (destinationLatitude BETWEEN $minLatitudeDestination AND $maxLatitudeDestination)";

//run the query
$result=mysqli_query($link,$sql);
if(!$result){
    echo "ERROR: Unable to execute: $sql. ". mysqli_error($link); exit;
}

if(mysqli_num_rows($result)==0){
    echo "<div class='alert alert-info noresults'>No match!</div>";
}
?>