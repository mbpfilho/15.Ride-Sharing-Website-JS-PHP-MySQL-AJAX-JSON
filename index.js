//ajax call signup form
//form submited
$("#signupForm").submit(function(event){ 
    //show spinner
    $("#spinner").show();
    //hide results
    $("#signupMessage").hide();   
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
                //hide spinner
                $("#spinner").hide();
                //ajax calls successful: show error or success message
                $("#signupMessage").html(data);
                //show results
                $("#signupMessage").slideDown(); 
            }
        },
        error: function(){
            //hide spinner
            $("#spinner").hide();
            //ajax call fails: show ajax call error
            $("#signupMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
            //show results
            $("#signupMessage").slideDown(); 
        }
    });
});


//ajax call login form
//form submited
$("#loginForm").submit(function(event){   
    //show spinner
    $("#spinner").show();
    //hide results
    $("#loginMessage").hide();     
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
                //hide spinner
                $("#spinner").hide();
                //otherwise show error message
                $("#loginMessage").html(data);
                $("#loginMessage").slideDown(); 
            }
        },
        error: function(){
            //hide spinner
            $("#spinner").hide();
            //ajax call fails: show ajax call error
            $("#loginMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
            //show results
            $("#loginMessage").slideDown(); 
        }
    });
});

//ajax call forgot password form
//form submited
$("#forgotForm").submit(function(event){   
    //show spinner
    $("#spinner").show();
    //hide results
    $("#forgotMessage").hide();     
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
            //hide spinner
            $("#spinner").hide();
            $("#forgotMessage").html(data);
            $("#forgotMessage").slideDown(); 
        },
        error: function(){
            //hide spinner
            $("#spinner").hide();
            //ajax call fails: show ajax call error
            $("#forgotMessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
            $("#forgotMessage").slideDown(); 
        }
    });
});

//create a geocoder object to use geocode googlemaps feature
var geocoder=new google.maps.Geocoder();

var data;

//submit the search form
$("#searchForm").submit(function(event){
    //show spinner
    $("#spinner").show();
    //hide results
    $("#searchResults").fadeOut();

    event.preventDefault();
    //collect users inputs
    data=$(this).serializeArray();
    getSearchDepartureCoordinates();

})

//define function
function getSearchDepartureCoordinates(){
    geocoder.geocode({
        "address":document.getElementById("departure").value
    },
    function(results,status){
        if(status==google.maps.GeocoderStatus.OK){
            departureLongitude=results[0].geometry.location.lng();
            departureLatitude=results[0].geometry.location.lat();
            data.push({name:'departureLongitude',value:departureLongitude});
            data.push({name:'departureLatitude',value:departureLatitude});
        }
        getSearchDestinationCoordinates();
    }
    )
}

function getSearchDestinationCoordinates(){
    geocoder.geocode({
        "address":document.getElementById("destination").value
    },
    function(results,status){
        if(status==google.maps.GeocoderStatus.OK){
            destinationLongitude=results[0].geometry.location.lng();
            destinationLatitude=results[0].geometry.location.lat();
            data.push({name:'destinationLongitude',value:destinationLongitude});
            data.push({name:'destinationLatitude',value:destinationLatitude});
        }
        submitSearchRequest();
    }
    )
}

function submitSearchRequest(){
    //send Ajax call to search.php
    $.ajax({
        url:"search.php",
        type:"POST",
        data: data,
        success:function(returnedData){
            //hide spinner
            $("#spinner").hide();
            $("#searchResults").html(returnedData);
            $("#tripResults").accordion({
                active: false,
                collapsible:true,
                heightStyle:"content",
                icons:false
            });
            //show results
            $("#searchResults").fadeIn();
        },
        error: function(){
            //hide spinner
            $("#spinner").hide();
            //ajax call fails: show ajax call error
            $("#searchResults").html("<div class='alert alert-danger'><strong>Ajax call error.</strong></div>");
            //show results
            $("#searchResults").fadeIn();
        }
    });
}