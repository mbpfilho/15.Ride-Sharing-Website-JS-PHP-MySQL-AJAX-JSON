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
                
            }
        }
    }else{
        echo "<div class='alert alert-warning'><strong>No trip yet</strong></div>";
    }
}

?>