<div class="debug_toolbar">
<h1>Content Personalization Debug Mode</h1>
<a id="DebugToolbarToggleButton">Toggle Debug Toolbar</a>
<span>This toolbar is shown for everyone as an experiment.</span>
<div class="debug_console">
<h2>Location</h2>
<p>Latitude: $Latitude, Longitude: $Longitude<br />$LocationString</p>
<h2>Device</h2>
<p>$Platform $Browser <br />User Agent: $UserAgent<br />Remote Addr: $RemoteAddr</p>
</div>
</div>
<% require css(sscp/css/DebugToolbar.css) %>
<script type="text/javascript">
	(function($){
		$("#DebugToolbarToggleButton").click(function(){
			$("div.debug_console").toggle("slow");
		})
	})(jQuery);
</script>