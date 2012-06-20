/*
 * ConditionBase.js provides functions for setting result of condition.
 */

// Getting location by using html5 geo-location api
if(navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(function(position) {
		alert("Your latitude is " + position.coords.latitude + " and longitude is " + position.coords.longitude);
		// We need to add geo-decode or reverse geo-coding.
		// Google maps reverse geo-coding apis is forbidden for using the api without Google Maps...
		// Thus, there is no API for this...
		// Geo-location API level2 is a choise?
	});
}

// Wrapper for editing cookie of CPEnvrionment