(function($){
	$("#DebugToolbarToggleButton").click(function(){
		$("div.debug_console").toggle("slow");
	});
	
	$("#ChangeLocationButton").click(function(){
		$("#ChangeLocationButton").attr('disabled', '');
		$("#ChangeLocationProcessing").show("slow");
		
		var lat = $("#LatitudeTextBox").val();
		var lon = $("#LongitudeTextBox").val();
		
		$.get('/sscpdebug/changelocation/', {"lat": lat, "lon": lon}, function(data){
			alert("Current location is changed successfully.You need to refresh this page to get the result.");
			$("#ChangeLocationProcessing").hide("slow");
			$("#ChangeLocationButton").removeAttr('disabled');
		});
	});
	
	$("#ClearLocationButton").click(function() {
		$("#ClearLocationButton").attr('disabled', '');
		$.get('/sscpdebug/clearLocation/', {}, function(data) {
			alert("Cleared location.");
			$("#ClearLocationButton").removeAttr('disabled');
		});
	});
})(jQuery);