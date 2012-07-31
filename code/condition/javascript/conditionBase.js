/*
 * ConditionBase.js provides functions for setting result of condition.
 */

// Get location by using html5 geo-location api and reverse-geo-coding api by OpenStreetMap.
// https://wiki.openstreetmap.org/wiki/Nominatim
(function($) {

var CPEnvKey = 'CPEnvJSON';
var CPEnvLocationKey = 'CPEnvLocationJSON';
var SourceIsHTML5 = 'HTML5-GeolocationAPI';
var CPEnvironment = {}; // Namespace for CPEnvironment
	
var getSourceOfLocation = function() {
	var address = JSON.parse($.cookie(CPEnvLocationKey));
	if(address != null) {
		switch (address.Source) {
			case "HTML5-GeolocationAPI":
				return "HTML5";
			case "IPInfoDB":
				return "IPInfoDB";
			case "DebugToolbar":
				return "DebugToolbar";
			case "Default":
				return "Default";
			default:
				return "Unknown";
		}
	} else {
		return "NotSet";
	}
};

var getLocationWithHtml5 = function(failed) {
	if(navigator.geolocation) {
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
							
						           address.lat = json.lat;
						           address.lon = json.lon;
						           
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
			},
			function(error) {
				failed();
			},
			{timeout:10000});
	} else {
		failed();
	}
};

var getLocationWithIPInfoDB = function(failed) {
	// Get IPAddress of the client, by calling the server.
	// We don't directly call IPInfoDB from Javascript, because it may show APIKey for visitors.
	$.ajax({
	    type: "GET",
	    url: "http://api.ipinfodb.com/v3/ip-city/",
	    data: { key: ipInfoDbAPIKey,
			  	format: 'json' },
	    dataType: "jsonp",
	    success: function(json) {
	    	if(json.statusCode === "OK") {
	    		var result = {
	    				lat: json.latitude,
						lon: json.longitude,
						Country: json.countryName,
						Region: json.regionName,
						City: json.cityName,
						Source: 'IPInfoDB' };
	    		$.cookie(CPEnvLocationKey, JSON.stringify(result));
	    	} else {
	    		failed();
	    	}
	    },
	    error: function(error) {
	    	failed();
	    }
	});
};

var doNothing = function() {};

$(function(){
	if(!(getSourceOfLocation() == "HTML5" || getSourceOfLocation() == "DebugToolbar")){
		getLocationWithHtml5(	getSourceOfLocation() == "IPInfoDB" ?
								doNothing :
								function(){ getLocationWithIPInfoDB(doNothing) });	
	}
});

// APIs for getting / setting values from / to CPEnvironment
// You can use these APIs if you want to get or set values from your javascript code.
// Namespace for this is CPEnvironment.
(function(){
	CPEnvironment = {
			get: function(key, defaultValue) {
				var value = $.parseJSON($.cookie(CPEnvKey));
				if(value == null || value[key] == null) {
					return defaultValue;
				} else {
					return value;
				}
			},
			set: function(key, value) {
				var value = $.parseJSON($.cookie(CPEnvKey));
				value[key] = value;
				$.cookie(CPEnvKey, JSON.stringify(CPEnvKey));
			}
	}
})();

})(jQuery);