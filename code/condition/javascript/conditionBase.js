/*
 * ConditionBase.js provides functions for setting result of condition.
 */

// Getting location by using html5 geo-location api

var CPEnvLocationKey = 'CPEnvLocationJ';

(function($) {

function locationSourceIsHTML5() {
	var address = JSON.parse($.cookie(CPEnvLocationKey));
	if(address != null && address.Source == 'HTML5-GeolocationAPI') {
		return true;
	} else {
		return false;
	}
}

if(!locationSourceIsHTML5() && navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(function(position) {
			alert("Your latitude is " + position.coords.latitude + " and longitude is " + position.coords.longitude);
			
			$.getJSON("http://nominatim.openstreetmap.org/reverse",
					{format: "json",
				lat: position.coords.latitude,
				lon: position.coords.longitude,
				zoom: 18,
				'accept-language': 'en'},
				function(json){
					var detailedAddress = json.address;
					var address = $.parseJSON($.cookie(CPEnvLocationKey));
					if(address == null) address = {};
					
					alert(JSON.stringify(address));
					
					address.Latitude = json.lat;
					address.Longitude = json.lon;
					address.Country = detailedAddress.country;
					address.Region = detailedAddress.state + ' ' + detailedAddress.region;
					address.City = detailedAddress.city;
					address.County = detailedAddress.county;
					address.Road = detailedAddress.road;
					address.PublicBuilding = detailedAddress.public_building;
					address.Postcode = detailedAddress.postcode;
					address.Source = 'HTML5-GeolocationAPI';
					
					$.cookie(CPEnvLocationKey,JSON.stringify(address));
				});
	});
}
})(jQuery);
