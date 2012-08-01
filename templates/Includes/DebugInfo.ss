<div class="DebugInfo">
	<p class="Title">$BlockHolderName</p>
	<div class="PropertyBox">
		<dl>
			<dt>Audience Type</dt>
			<dd>$AppliedAudienceType</dd>
			<dt>Snippet</dt>
			<dd>$RenderedSnippetName</dd>
			<dt>Considered Audience Types</dt>
			<dd>
				<ul>
				<% loop $ConsideredAudienceTypes %>
					<li>$Name</li>
				<% end_loop %>
				</ul>
			</dd>
		</dl>
	</div>
</div>