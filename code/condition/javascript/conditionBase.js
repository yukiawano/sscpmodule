/*
 * ConditionBase.js provides functions for setting result of condition.
 */

// Get location by using html5 geo-location api and reverse-geo-coding api by OpenStreetMap.
// https://wiki.openstreetmap.org/wiki/Nominatim

var CPEnvKey = 'CPEnv';
var CPEnvLocationKey = 'CPEnvLocationJ';
var SourceIsHTML5 = 'HTML5-GeolocationAPI';

(function($) {

// Geo-location
function locationSourceIsHTML5() {
	var address = JSON.parse($.cookie(CPEnvLocationKey));
	if(address != null && address.Source == SourceIsHTML5) {
		return true;
	} else {
		return false;
	}
}

if(!locationSourceIsHTML5() && navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(
		function(position) {
			$.getJSON( "http://nominatim.openstreetmap.org/reverse",
					   { format: "json",
				         lat: position.coords.latitude,
				         lon: position.coords.longitude,
				         zoom: 18,
				         'accept-language': 'en' },
				       function(json){
				           var detailedAddress = json.address;
				           
				           var address = $.parseJSON($.cookie(CPEnvLocationKey));
				           if(address == null) address = {};
					
				           address.Latitude = json.lat;
				           address.Longitude = json.lon;
				           
				           address.Country = detailedAddress.country;
				           address.Region = detailedAddress.state + ' ' + detailedAddress.region;
				           address.City = detailedAddress.city;
				           address.County = detailedAddress.county;
				           address.Road = detailedAddress.road;
				           address.PublicBuilding = detailedAddress.public_building;
				           address.Postcode = detailedAddress.postcode;
				           address.Source = SourceIsHTML5;
					
				           $.cookie(CPEnvLocationKey,JSON.stringify(address));
				       }
				     );
	});
}

// APIs for getting / setting values from / to CPEnvironment
// You can use these APIs if you want to get or set values from your javascript code.

function CPEnvGet(key, defaultValue) {
	var value = $.parseJSON($.cookie(CPEnvKey));
	if(value == null || value[key] == null) {
		return defaultValue;
	} else {
		return value;
	}
}

function CPEnvSet(key, value) {
	var value = $.parseJSON($.cookie(CPEnvKey));
	value[key] = value;
	$.cookie(CPEnvKey, JSON.stringify(CPEnvKey));
}

})(jQuery);
