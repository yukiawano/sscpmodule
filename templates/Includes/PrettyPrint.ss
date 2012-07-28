<p>MatchingRule: $MatchingRule</p>
<ul>
<% control $AudienceTypes %>
<li>
	<strong>$Name</strong>
	<ul>
	<% control $Conditions %>
		<li>{$Name}: $Args</li>
	<% end_control %>
	</ul>
</li>
<% end_control %>
</ul>