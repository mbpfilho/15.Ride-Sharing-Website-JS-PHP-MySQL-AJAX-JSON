<?php
session_start();
if(!isset($_SESSION["user_id"])){
    header("location:index.php");
}
$username=$_SESSION["username"];
$email=$_SESSION["email"];

// include("connection.php");
// $user_id=$_SESSION["user_id"];

// //get username and email
// $sql="SELECT * FROM users WHERE user_id='$user_id'";
// $result=mysqli_query($link,$sql);
// //store number of rows
// $count=mysqli_num_rows($result);

// if($count==1){
//     $row=mysqli_fetch_array($result,MYSQL_ASSOC);
//     $username=$row["username"];
//     $email=$row["email"];
// }else{
//     echo"Error retrieving username and email from the database";
// }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Profile</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo&family=Open+Sans&family=Source+Sans+Pro&family=Vollkorn&display=swap" rel="stylesheet">

    <link href="styling.css" rel="stylesheet">

    <style>
        #container{
            margin-top: 100px;
        }

        #notePad,#allNotes,#done{
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

        tr{
            cursor: pointer;
        }

        .preview{
            height: 20px;
            border-radius: 50%;
        }
        .preview2{
            height: 200px;
            max-width: 100%;
            border-radius: 50%;
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
                    <li class="active"><a href="#">Profile</a></li>
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="mainpage.php">My Trips</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"><div data-toggle="modal" data-target="#updatepicture"><img class="preview" src="cn.jpg" alt="profile picture"></div></a></li>
                    <li><a href="#"><?php echo $username;?></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- container -->
    <div class="container" id="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <h2>General Account Settings:</h2>
                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-bordered">
                        <tr data-target="#updateusername" data-toggle="modal">
                            <td>Username</td>
                            <td><?php echo $username;?></td>
                        </tr>
                        <tr data-target="#updateemail" data-toggle="modal">
                            <td>Email</td>
                            <td><?php echo $email;?></td>
                        </tr>
                        <tr data-target="#updatepassword" data-toggle="modal">
                            <td>Password</td>
                            <td>******</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- update username form -->
    <form method="post" id="updateusernameForm">
        <div class="modal" id="updateusername" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Edit Username:</h4>
                </div>
                <div class="modal-body">
                    <!-- login message from php file -->
                    <div id="updateusernamemessage"></div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input class="form-control" id="username" type="text" name="username" maxlength="30" value="<?php echo $username;?>">
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="btn green" name="updateusername" type="submit" value="Submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <!-- update email form -->
    <form method="post" id="updateemailForm">
        <div class="modal" id="updateemail" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Enter new email:</h4>
                </div>
                <div class="modal-body">

                    <!-- update email message from php file -->
                    <div id="updateemailmessage"></div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input class="form-control" id="email" type="email" name="email" maxlength="50" value="<?php echo $email;?>">
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="btn green" name="updateusername" type="submit" value="Submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <!-- update password form -->
    <form method="post" id="updatepasswordForm">
        <div class="modal" id="updatepassword" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Enter current and new passwords:</h4>
                </div>
                <div class="modal-body">
                    <!-- update password message from php file -->
                    <div id="updatepasswordmessage"></div>

                    <div class="form-group">
                        <label for="currentpassword" class="sr-only">Current password:</label>
                        <input class="form-control" id="currentpassword" type="password" name="currentpassword" maxlength="30" placeholder="Current password">
                    </div>

                    <div class="form-group">
                        <label for="password" class="sr-only">New password:</label>
                        <input class="form-control" id="password" type="password" name="password" maxlength="30" placeholder="New password">
                    </div>

                    <div class="form-group">
                        <label for="password2" class="sr-only">Confirm password:</label>
                        <input class="form-control" id="password2" type="password" name="password2" maxlength="30" placeholder="Confirm password">
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="btn green" name="updateusername" type="submit" value="Submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </div>
        </div>
    </form>

    <!-- update picture form -->
    <form method="post" id="updatepictureform">
        <div class="modal" id="updatepicture" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 id="myModalLabel">Update Picture:</h4>
                </div>
                <div class="modal-body">
                    <!-- update picture message from php file -->
                    <div id="updatepicturemessage"></div>

                    <div><img class="preview2" src="cn.jpg" alt="profile picture"></div>

                    <div class="form-group">
                        <label for="picture">Select a picture:</label>
                        <input id="picture" type="file" name="picture">
                    </div>

                </div>
                <div class="modal-footer">
                    <input class="btn green" name="updateusername" type="submit" value="Submit">
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

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    
    <!-- link to javascript file -->
    <script src="profile.js"></script>
  </body>
</html>