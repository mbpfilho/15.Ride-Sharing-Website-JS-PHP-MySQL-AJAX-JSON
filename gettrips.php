<?php
session_start();
include("connection.php");

$user_id=$_SESSION["user_id"];

$sql="SELECT * FROM carsharetrips WHERE user_id='$user_id'";

$result=mysqli_query($link,$sql);

if($result){
    // print_r($result);
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
            //check frequency
            if($row["regular"]=="N"){
                $frequency="One-off journey";
                $time=$row["date"]." at ".$row["time"];
            }else{
                $frequency="Regular";
                $array=[];
                if($row["sunday"]==1){array_push($array,"Sun");}
                if($row["monday"]==1){array_push($array,"Mon");}
                if($row["tuesday"]==1){array_push($array,"Tue");}
                if($row["wednesday"]==1){array_push($array,"Wed");}
                if($row["thursday"]==1){array_push($array,"Thu");}
                if($row["friday"]==1){array_push($array,"Fri");}
                if($row["saturday"]==1){array_push($array,"Sat");}
                $time=implode("-",$array)." at ".$row["time"];
            }
            $departure=$row["departure"];
            $destination=$row["destination"];
            $price=$row["price"];
            $seatsAvailable=$row["seatsavailable"];
            $trip_id=$row["trip_id"];
            
            echo"
            <div class='row trip'>
                <div class='col-xs-8 journey'>
                    <div><span class='departure'>Departure:</span>$departure</div>
                    <div><span class='destination'>Destination:</span>$destination</div>
                    <div class='time'>$time</div>
                    <div>$frequency</div>
                </div>
                <div class='col-xs-2'>
                    <div class='price'>$$price</div>
                    <div class='perseat'>Per seat</div>
                    <div class='seatsAvailable'>$seatsAvailable left</div>
                </div>
                <div class='col-xs-2'>
                    <button class='btn blue' data-toggle='modal' data-target='#edittripModal' data-trip_id='$trip_id'>Edit</button>
                </div>
            </div>";
        }
    }else{
        echo "<div class='alert alert-warning'><strong>No trip yet</strong></div>";
    }
}

?>