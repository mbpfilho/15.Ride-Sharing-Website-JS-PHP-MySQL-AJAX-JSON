<?php
// if user in not logged in and remerberme cookies exist
if(!isset($_SESSION["user_id"]) && !empty($_COOKIE["rememberme"])){    // array_key_exists("user_id",$_SESSION)
    //COOKIE f1 $a.",".bin2hex($b)
    // f2 hash("sha256",$a)
    //     extract $authenticators 1&2 from the cookie
    list($authentificator1,$authentificator2)=explode(",",$_COOKIE["rememberme"]);
    $authentificator2=hex2bin($authentificator2);
    $f2authentificator2=hash("sha256",$authentificator2);

    //     look for authenticator1 in the rememberme table
    $sql="SELECT * FROM rememberme WHERE authentificator1='$authentificator1'";
    $result=mysqli_query($link,$sql);
    if(!$result){
        // echo "<div class='alert alert-danger'><p>Error running the query.</p><p>".mysqli_error($link)."</p></div>";
        echo '<script>alert("Error running the query.")</script>';
        //destroy cookie
        setcookie("rememberme","",time()-3600);
        //return to index.php
        header("location:index.php");
        exit;
    }
    $count=mysqli_num_rows($result); 
    if($count !== 1){
        // echo "<div class='alert alert-danger'>Remember me process failed.</div>";
        echo '<script>alert("Remember me process failed.")</script>';
    //destroy cookie
    setcookie("rememberme","",time()-3600);
    //return to index.php
        header("location:index.php");
        exit;
    }
    //     else

    $row=mysqli_fetch_array($result,MYSQLI_ASSOC); //extract data from table
    if(!hash_equals($row["f2authentificator2"],$f2authentificator2)){
        //     if authenticator2 does not match
    //         print error
        // echo "<div class='alert alert-danger'>Authentificators do not match.</div>";
        echo '<script>alert("Authentificators do not match.")</script>';
        //destroy cookie
        setcookie("rememberme","",time()-3600);
        //return to index.php
        header("location:index.php");
        exit;
    }

    //         generet a new authenticators
    //             <!-- create two variables $authentications1 and authentication2 -->
    $authentificator1=bin2hex(openssl_random_pseudo_bytes(10));
    $authentificator2=openssl_random_pseudo_bytes(20);
    //             <!-- store them in a cookie -->
    function f1($a,$b){
        $c=$a.",".bin2hex($b);
        return $c;
    }
    $cookieValue=f1($authentificator1,$authentificator2);
    setcookie(
        "rememberme",
        $cookieValue,
        time()+15*24*60*60
    );
    function f2($a){
        $b=hash("sha256",$a);
        return $b;
    }
    $f2authentificator2=f2($authentificator2);
    // $user_id=$_SESSION["user_id"];
    $expiration=date("Y-m-d H:i:s",time()+15*24*60*60);
    // runquery to update(store) them in rememberme table
    // $sql="UPDATE rememberme SET authentificator1,f2authentificator2,user_id,expires) VALUES ('$authentificator1','$f2authentificator2','$user_id','$expiration')"; 
    //extract id form rememberme table
    $id=$row["id"];
    $sql="UPDATE rememberme SET authentificator1='$authentificator1', f2authentificator2='$f2authentificator2', expires='$expiration' WHERE id='$id'";
    $result=mysqli_query($link,$sql);
    //             <!-- run query unsuccessful -->
    if(!$result){
        //                 <!-- print error -->
        echo "<div class='alert alert-danger'><p>Error updating remember data:</p><p>".mysqli_error($link)."</p></div>";
    }
    //set $_SESSION["id"]
    $_SESSION["id"]=$id;
    //         log user and redirect to notes page
    $_SESSION["user_id"]=$row["user_id"];
    header("location:mainpage.php");
}
// else{
//     echo "<div class='alert alert-danger' style='margin-top:50px'><p>User_id:</p><p>".$_SESSION["user_id"]."</p></div>";
//     echo "<div class='alert alert-danger'><p>Cookie value:</p><p>".$_SESSION["rememberme"]."</p></div>";
// }

?>