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
$sql="SELECT * FROM carsharetrips WHERE ";

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
    echo "<div class='alert alert-info noresult'>No match!</div>";
    exit;
}

echo "<div class='alert alert-info'>From $departure to $destination.<br>Closest journeys:</div>";

echo "<div id='tripResults'>";

//cycle through the trips
while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
    //get trip details
    //check frequency
    if($row["regular"]=="N"){
        $frequency="One-off journey";
        $time=$row["date"]." at ".$row["time"];
        //check the date
        $tripDate=DateTime::createFromFormat("D d M, Y",$row["date"]);
        $todayDate=DateTime::createFromFormat("D d M, Y",date("D d M, Y"));
        if($tripDate<$todayDate){continue;}
    }else{
        $frequency="Regular";
        $array=[];
        // if($row["sunday"]==1){array_push($array,"Sun");}
        // if($row["monday"]==1){array_push($array,"Mon");}
        // if($row["tuesday"]==1){array_push($array,"Tue");}
        // if($row["wednesday"]==1){array_push($array,"Wed");}
        // if($row["thursday"]==1){array_push($array,"Thu");}
        // if($row["friday"]==1){array_push($array,"Fri");}
        // if($row["saturday"]==1){array_push($array,"Sat");}
        $weekdays=["sunday"=>"Sun","monday"=>"Mon","tuesday"=>"Tue","wednesday"=>"Wed","thursday"=>"Thu","friday"=>"Fri","saturday"=>"Sat"];
        foreach($weekdays as $key=>$value){
            if($row[$key]==1){
                array_push($array,$value);
            }
        }
        $time=implode("-",$array)." at ".$row["time"];
    }
    $tripDeparture=$row["departure"];
    $tripDestination=$row["destination"];
    $price=$row["price"];
    $seatsAvailable=$row["seatsavailable"];

    //get user_id
    $person_id=$row["user_id"];

    //run query do get user details
    $sql2="SELECT * FROM users WHERE user_id='$person_id' LIMIT 1";
    $result2=mysqli_query($link,$sql2);
    if(!$result2){
        echo "ERROR: Unable to execute: $sql2. ". mysqli_error($link); exit;
    }

    $row2=mysqli_fetch_array($result2,MYSQLI_ASSOC);
    $firstname=$row2["first_name"];
    $gender=$row2["gender"];
    $moreinformation=$row2["moreinformation"];
    $picture=$row2["profilepicture"];
    if(isset($_SESSION["user_id"])){
        $phonenumber=$row2["phonenumber"]; 
    }else{
        $phonenumber="Members only. Sign up!"; 
    }
    
    //print trip
    echo "<h4 class='row'>
        <div class='col-xs-2'>
            <div class='driver'>$firstname</div>
            <div><img class='profile' src='$picture' alt='driver picture'></div>
        </div>
        <div class='col-xs-8 journey'>
            <div><span class='departure'>Departure:</span>$tripDeparture</div>
            <div><span class='destination'>Destination:</span>$tripDestination</div>
            <div class='time'>$time</div>
            <div>$frequency</div>
        </div>
        <div class='col-xs-2 journey2'>
        <div class='price'>$$price</div>
        <div class='perseat'>Per seat</div>
        <div class='seatsAvailable'>$seatsAvailable left</div>
        </div>
    </h4>
    <div class='moreinfo'>
        <div>
            <div>Gender: $gender</div>
            <div>&#9742: $phonenumber</div>
        </div>
        <div class='about'>About: $moreinformation</div>
    </div>";

}

echo "</div>";
?>