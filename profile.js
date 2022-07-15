//Ajax to updateusername.php
$("#updateusernameForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to updateusername.php using ajax
    $.ajax({
        url:"updateusername.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            if(data){
                //ajax calls successful: show error or success message
                $("#updateusernamemessage").html(data);
            }else{
                location.reload();
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#updateusernamemessage").html("<div class='alert alert-danger'>Ajax call error</div>");
        }
    });
});

//Ajax call updatepassword.php
$("#updatepasswordForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to updateusername.php using ajax
    $.ajax({
        url:"updatepassword.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            if(data){
                //ajax calls successful: show error or success message
                $("#updatepasswordmessage").html(data);
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#updatepasswordmessage").html("<div class='alert alert-danger'>Ajax call error</div>");
        }
    });
});


//Ajax call updateemail.php
$("#updateemailForm").submit(function(event){    
    //prevent default php processing
    event.preventDefault();
    //collect users inputs
    var datatopost=$(this).serializeArray();
    //send them to updateusername.php using ajax
    $.ajax({
        url:"updateemail.php",
        type:"POST",
        data: datatopost,
        success:function(data){
            if(data){
                //ajax calls successful: show error or success message
                $("#updateemailmessage").html(data);
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#updateemailmessage").html("<div class='alert alert-danger'>Ajax call error</div>");
        }
    });
});
