<?php
if(isset($_SESSION["user_id"]) && isset($_GET["logout"])){
    if($_GET["logout"]==1){
        if(isset($_SESSION["id"])){
            $id=$_SESSION["id"];
            $sql="DELETE FROM rememberme WHERE id='$id'";
            $result=mysqli_query($link,$sql);
            if(!$result){
                // echo "<div class='alert alert-danger'><p>Query error deleting from rememberme table.</p><p>".mysqli_error($link)."</p></div>";
                echo '<script>alert("Query error deleting from rememberme table.")</script>';
                exit;
            }
        }
        //destroy session
        session_destroy();
        //destroy cookie
        setcookie("rememberme","",time()-3600);
        //reload index.php
        header('Location: index.php');
    }
}
?>