<div class="debug_toolbar">
<h1>Content Personalization Debug Mode</h1>
<a id="DebugToolbarToggleButton">Toggle Debug Toolbar</a>
<div class="debug_console">
<h2>Location</h2>
<p>
	Latitude <input type="text" value="$Latitude" id="LatitudeTextBox" style="width:100px;"></input>
	Longitude<input type="text" value="$Longitude" id="LongitudeTextBox" style="width:100px;"></input>
	<input type="button" value="Change" id="ChangeLocationButton"></input>
	<input type="button" value="Clear" id="ClearLocationButton"></input>
	<span id="ChangeLocationProcessing">Processing...</span>
</p>
<p>Address: $LocationString<br />Source: $LocationSource</p>
<h2>Device & Environment</h2>
<p>OS: $Platform / Browser: $Browser <br />User Agent: $UserAgent<br />Remote Addr: $RemoteAddr</p>
</div>
</div>
<% require css(sscp/css/DebugToolbar.css) %>
<% require javascript(sscp/javascript/DebugToolbar.css) %>