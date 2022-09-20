//get trips
getTrips();

//create a geocoder object to use geocode googlemaps feature
var geocoder=new google.maps.Geocoder();

$(function(){
    //fix map
    $("#addtripModal").on("shown.bs.modal",function(){
        google.maps.event.trigger(map,"resize");
    })
});

//hide all date-time inputs
$(".regular").hide();
$(".one-off").hide();
$(".regular2").hide();
$(".one-off2").hide();

var myRadio=$("input[name='regular']");

myRadio.click(function(){
    if($(this).is(":checked")){
        if($(this).val()=="Y"){
            $(".one-off").hide(); 
            $(".regular").show();           
        }else{
            $(".regular").hide();
            $(".one-off").show();            
        }
    }
})

var myRadio2=$("input[name='regular2']");

myRadio2.click(function(){
    if($(this).is(":checked")){
        if($(this).val()=="Y"){
            $(".one-off2").hide(); 
            $(".regular2").show();           
        }else{
            $(".regular2").hide();
            $(".one-off2").show();            
        }
    }
})

//calendar
$("input[name='date'],input[name='date2']").datepicker({
    numberOfMonths: 1,
    showAnim: "fadeIn",
    dateFormat: "D d M,yy",
    minDate: +1,
    maxDate:"+12M",
    showWeek: true
})

var data;
var departureLatitude;
var departureLongitude;
var destinationLatitude;
var destinationLongitude;
var trip;

//click on create trip button
$("#addtripForm").submit(function(event){
    event.preventDefault();
    data=$(this).serializeArray();
    // console.log(data);
    getAddTripDepartureCoordinates();
})

//define functions
function getAddTripDepartureCoordinates(){
    geocoder.geocode({
        "address":document.getElementById("departure").value
    },
    function(results,status){
        if(status==google.maps.GeocoderStatus.OK){
            departureLongitude=results[0].geometry.location.lng();
            departureLatitude=results[0].geometry.location.lat();
            data.push({name:'departureLongitude',value:departureLongitude});
            data.push({name:'departureLatitude',value:departureLatitude});
            getAddTripDestinationCoordinates();
        }else{
            getAddTripDestinationCoordinates();
        }
    }
    )
}

function getAddTripDestinationCoordinates(){
    geocoder.geocode({
        "address":document.getElementById("destination").value
    },
    function(results,status){
        if(status==google.maps.GeocoderStatus.OK){
            destinationLongitude=results[0].geometry.location.lng();
            destinationLatitude=results[0].geometry.location.lat();
            data.push({name:'destinationLongitude',value:destinationLongitude});
            data.push({name:'destinationLatitude',value:destinationLatitude});
            submitAddTripRequest();
        }else{
            submitAddTripRequest();
        }
    }
    )
}

function submitAddTripRequest(){
    //send Ajax call to addtrip.php
    $.ajax({
        url:"addtrip.php",
        type:"POST",
        data: data,
        success:function(returnedData){
            if(returnedData){
                $("#addtripmessage").html(returnedData);
            }else{
                //hide modal
                $("#addtripModal").modal('hide');
                //reset form
                $("#addtripForm")[0].reset();
                //load trips
                getTrips();
            }
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#addtripmessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
        }
    });
}

function formatModal(){
    $("#departure2").val(trip["departure"]);
    $("#destination2").val(trip["destination"]);
    $("#price2").val(trip["price"]);
    $("#seatsavailable2").val(trip["seatsavailable"]);
    if(trip["regular"]=="Y"){
        $("#yes2").prop("checked",true);
        $("#sunday2").prop("checked",trip["sunday"]=="1");
        $("#monday2").prop("checked",trip["monday"]=="1");
        $("#tuesday2").prop("checked",trip["tuesday"]=="1");
        $("#wednesday2").prop("checked",trip["wednesday"]=="1");
        $("#thursday2").prop("checked",trip["thursday"]=="1");
        $("#friday2").prop("checked",trip["friday"]=="1");
        $("#saturday2").prop("checked",trip["saturday"]=="1");
        $("input[name='time2']").val(trip["time"]);
        $(".one-off2").hide();
        $(".regular2").show();
    }else{
        $("#no2").prop("checked",true);
        $("input[name='date2']").val(trip["date"]);
        $("input[name='time2']").val(trip["time"]);
        $(".regular2").hide();
        $(".one-off2").show();
    }
}

//get Trips
function getTrips(){
    //send Ajax call to gettrips.php
    $.ajax({
        url:"gettrips.php",
        success:function(returnedData){
                $("#myTrips").html(returnedData);
        },
        error: function(){
            //ajax call fails: show ajax call error
            $("#myTrips").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
        }
    });
}

    //click on edit button inside a trip
    $("#edittripModal").on("show.bs.modal",function(event){
        $("#edittripmessage").empty();

        //button which opened the modal
        var invoker=$(event.relatedTarget);

        //ajax call to get details of the trip
        $.ajax({
            url:"gettripdetails.php",
            method:"POST",
            data:{trip_id:invoker.data("trip_id")},
            success:function(returnedData){
                if(returnedData){
                    if(returnedData=="error"){
                        $("#edittripmessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
                    }else{
                        trip=JSON.parse(returnedData);
                        //fill edit trip form using the JSON parsed data
                        formatModal();
                    }
                }
            },
            error: function(){
                //ajax call fails: show ajax call error
                $("#edittripmessage").html("<div class='alert alert-danger'><strong>Ajax call error</strong></div>");
            }
        });
    })