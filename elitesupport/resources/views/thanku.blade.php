<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Ashok Leyland ! Thank You</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('js/jquery-min.js')}}"></script>
</head>
<body onload="getLocation()">
	<div class="content-wrapper mobcss">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h1 style="text-align: center;color: darkgreen" id="thank">Thank You</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
   
function getLocation() {
    /* var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
    if (mobile) {
       
        alert(lat+'  '+long+'  '+phoneNumber);
    }else{
        alert("Not Mobile");
    }  */
    // if (navigator.geolocation) {       
    //     var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
    //     if (mobile) {
    //         navigator.geolocation.getCurrentPosition(showPosition);
    //     }else{
    //         alert("Not Mobile");
    //     } 
    // } else {
    //     alert("Geolocation is not supported by this browser.");
    // }

    var successHandler = function(position) { 
        var phoneNumber = '{{isset($phoneNumber)?$phoneNumber:''}}';
        var sessionId = '{{isset($sessionId)?$sessionId:''}}';
        
        var lat =  position.coords.latitude;
        var long = position.coords.longitude; 
        $.ajax({ url: '{{url("insert-latlong")}}',
		data: { 'lat': lat,'long': long,'phoneNumber': phoneNumber,'sessionId': sessionId },
		success: function(data){
            console.log(data);
		}
	});  
}; 

var errorHandler = function (errorObj) { 
    alert(errorObj.code + ": " + errorObj.message);
    $('#thank').hide();
}; 

navigator.geolocation.getCurrentPosition( 
successHandler, errorHandler, 
{enableHighAccuracy: true, maximumAge: 10000});
    
}
/* function showPosition(position) {
        alert("aaaaa");
        var phoneNumber = '{{isset($phoneNumber)?$phoneNumber:''}}';
        var lat =  position.coords.latitude;
        var long = position.coords.longitude; 
    
       
    
    var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
    if (mobile) {
        console.log("In");  
        alert(lat+'  '+long+'  '+phoneNumber);
    }else{
        alert("Not Mobile");
    } 
    console.log("test"); 
    $.ajax({ url: '{{url("insert-latlong")}}',
		data: { 'lat': lat,'long': long,'phoneNumber': phoneNumber },
		success: function(data){
            console.log(data);
		}
	});
} */
</script>
</html>


