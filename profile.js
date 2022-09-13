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

//update picture preview
var file;
var imageType;
var imageSize;
var wrongType;
$("#picture").change(function(){
    file=this.files[0];
    console.log(file);
    imageType=file.type;
    imageSize=file.size;

    //check image type
    var acceptableTypes=["image/jpeg","image/jpg","image/png"];
    wrongType=($.inArray(imageType,acceptableTypes)==-1);
    if(wrongType){
        $("#updatepicturemessage").html("<div class='alert alert-danger'>Only jpeg, jpg and png are accepted</div>");
        return false;
    }
    //check image size
    if(imageSize>3*1024*1024){
        $("#updatepicturemessage").html("<div class='alert alert-danger'>Up to 3M image</div>");
        return false;
    }

    //the FileReader object will be used to convert our image to a binary string
    var reader=new FileReader();
    //callback
    reader.onload=updatePreview;
    //strat the read operation -> convert content into a data URL which is passed to the callback
    reader.readAsDataURL(file);
});

function updatePreview(event){
    console.log(event);
    $("#preview2").attr("src",event.target.result);
}

//update picture
$("#updatepictureform").submit(function(event){
    event.preventDefault();
    
    //test file missing
    if(!file){
        $("#updatepicturemessage").html("<div class='alert alert-danger'>Choose a new picture</div>");
        return false;
    }

    //check image type
    if(wrongType){
        $("#updatepicturemessage").html("<div class='alert alert-danger'>Only jpeg, jpg and png are accepted</div>");
        return false;
    }
    //check image size
    if(imageSize>3*1024*1024){
        $("#updatepicturemessage").html("<div class='alert alert-danger'>Up to 3M image</div>");
        return false;
    }
    
    //send Ajax call to updatepicture.php
    $.ajax({
        url:"updatepicture.php",
        type:"POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data){
            if(data){
                $("#updatepicturemessage").html(data); 
            }else{
                location.reload();
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#updatepicturemessage").html("<div class='alert alert-danger'>Ajax call error</div>");
        }
    });
})