$(function(){
    //fix map
    $("#addtripModal").on("shown.bs.modal",function(){
        google.maps.event.trigger(map,"resize");
    })
});

//hide all date-time inputs
$(".regular").hide();
$(".one-off").hide();

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