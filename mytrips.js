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
            console.log(data);
        }else{
        }
    }
    )
}