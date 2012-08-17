<% if $Blocks %>
<div id="slides">
	<div class="slides_container">
		<% control $Blocks %>
		<div>
			$Body
		</div>
		<% end_control %>
	</div>
</div>
<% else %>
	<p>There is no slide</p>
<% end_if %>
