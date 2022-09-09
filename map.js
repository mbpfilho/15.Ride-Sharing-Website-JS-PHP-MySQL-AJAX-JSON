var map;
//set map iptions
var myLatLng={lat:51.51,lng:-.13};
var mapOptions={
    center: myLatLng,
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP
}

//onload:
google.maps.event.addDomListener(window,"load",initialize);

//initialize: draw map in the #googleMap div
function initialize(){
    //create map
    map=new google.maps.Map(document.getElementById("googleMap"),mapOptions);
}