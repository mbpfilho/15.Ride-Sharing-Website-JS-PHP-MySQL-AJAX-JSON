//ajax call signup form
//form submited
$("#signupForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to signup.php using ajax
    $.ajax({
        url:"signup.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            if(data){
                //ajax calls successful: show error or success message
                $("#signupMessage").html(data);
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#signupMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
        }
    });
});


//ajax call login form
//form submited
$("#loginForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to login.php using ajax
    $.ajax({
        url:"login.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            if(data=="success"){
                //if php files return success:redirect to notes page
                window.location="mainpage.php";
            }else{
                //otherwise show error message
                $("#loginMessage").html(data);
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#loginMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
        }
    });
});

//ajax call forgot password form
//form submited
$("#forgotForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to forgot-password.php using ajax
    $.ajax({
        url:"forgot-password.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            $("#forgotMessage").html(data);
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#forgotMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
        }
    });
});
