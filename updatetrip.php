<?php
session_start();
include("connection.php");

//define error messages
$missingDeparture="<p><strong>Enter a departure</strong></p>";
$invalidDeparture="<p><strong>Enter a valid departure</strong></p>";
$missingDestination="<p><strong>Enter a destination</strong></p>";
$invalidDestination="<p><strong>Enter a valid destination</strong></p>";
$missingPrice="<p><strong>Choose a price per seat</strong></p>";
$invalidPrice="<p><strong>Enter a valid price. Numbers only</strong></p>";
$missingSeatsavailable="<p><strong>Select the number of available seats</strong></p>";
$invalidSeatsavailable="<p><strong>Numbers only on available seats</strong></p>";
$missingFrequency="<p><strong>Select a frequency</strong></p>";
$missingDays="<p><strong>Select at least one weekday</strong></p>";
$missingDate="<p><strong>Choose a date for the trip</strong></p>";
$missingTime="<p><strong>Choose a time for the trip</strong></p>";
$errors="";

//get inputs
$trip_id=$_POST["trip_id"];
$departure=$_POST["departure2"];
$destination=$_POST["destination2"];
$price=$_POST["price2"];
$seatsavailable=$_POST["seatsavailable2"];
$time=$_POST["time2"];
$date=$_POST["date2"];
$regular=(isset($_POST["regular2"]))?$_POST["regular2"]:"";
// if(isset($_POST["sunday"])){$sunday=$_POST["sunday"];}
$sunday=(isset($_POST["sunday2"]))?$_POST["sunday2"]:"";
$monday=(isset($_POST["monday2"]))?$_POST["monday2"]:"";
$tuesday=(isset($_POST["tuesday2"]))?$_POST["tuesday2"]:"";
$wednesday=(isset($_POST["wednesday2"]))?$_POST["wednesday2"]:"";
$thursday=(isset($_POST["thursday2"]))?$_POST["thursday2"]:"";
$friday=(isset($_POST["friday2"]))?$_POST["friday2"]:"";
$saturday=(isset($_POST["saturday2"]))?$_POST["saturday2"]:"";

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

//check price
if(empty($price)){
    $errors.=$missingPrice;
}elseif(preg_match("/\D/",$price)){
    $errors.=$invalidPrice;
}else{
    $price=htmlspecialchars($price);
}

//check seats available
if(empty($seatsavailable)){
    $errors.=$missingSeatsavailable;
}elseif(preg_match("/\D/",$seatsavailable)){
    $errors.=$invalidSeatsavailable;
}else{
    $seatsavailable=htmlspecialchars($seatsavailable);
}

//check frequency
if(empty($regular)){
    $errors.=$missingFrequency;
}elseif($regular=="Y"){
    if(empty($sunday)&&empty($monday)&&empty($tuesday)&&empty($wednesday)&&empty($thursday)&&empty($friday)&&empty($saturday)){
        $errors.=$missingDays;
    }
    if(empty($time)){
        $errors.=$missingTime;
    }
}else{
    if(empty($date)){
        $errors.=$missingDate;
    }
    if(empty($time)){
        $errors.=$missingTime;
    }
}

//check if there is an error and print an error message
if($errors){
    echo "<div class='alert alert-danger'>$errors</div>";
}else{
    //no error -> prepare variables to the query
    $departure=mysqli_real_escape_string($link,$departure);
    $destination=mysqli_real_escape_string($link,$destination);
    $user_id=$_SESSION["user_id"];
    
    // if($regular=="Y"){
    //     //query for a regular trip

    // }else{
    //     //query for a one-off trip
    // }

    $sql="UPDATE carsharetrips SET departure='$departure',departureLongitude='$departureLongitude',departureLatitude='$departureLatitude',destination='$destination',destinationLongitude='$destinationLongitude',destinationLatitude='$destinationLatitude',price='$price',seatsavailable='$seatsavailable',regular='$regular',date='$date',time='$time',sunday='$sunday',monday='$monday',tuesday='$tuesday',wednesday='$wednesday',thursday='$thursday',friday='$friday',saturday='$saturday' WHERE trip_id='$trip_id' LIMIT 1";

    $result=mysqli_query($link,$sql);

    //heck if query is successful
    if(!$result){
        echo "<div class='alert alert-danger'>Query error. Trip not updated.</div>";
    }
}

?>