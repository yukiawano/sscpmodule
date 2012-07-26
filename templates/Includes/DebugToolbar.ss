<div class="debug_toolbar">
<h1>Content Personalization Debug Mode</h1>
<a id="DebugToolbarToggleButton">Toggle Debug Toolbar</a>
<span>This toolbar is shown for everyone as an experiment.</span>
<div class="debug_console">
<h2>Location</h2>
<p>
	Latitude <input type="text" value="$Latitude" id="LatitudeTextBox" style="width:100px;"></input>
	Longitude<input type="text" value="$Longitude" id="LongitudeTextBox" style="width:100px;"></input>
	<input type="button" value="Change" id="ChangeLocationButton"></input>
	<span id="ChangeLocationProcessing">Processing...</span>
</p>
<p>Address: $LocationString<br />Source: $LocationSource</p>
<h2>Device & Environment</h2>
<p>OS: $Platform / Browser: $Browser <br />User Agent: $UserAgent<br />Remote Addr: $RemoteAddr</p>
</div>
</div>
<% require css(sscp/css/DebugToolbar.css) %>
<script type="text/javascript">
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
	})(jQuery);
</script>