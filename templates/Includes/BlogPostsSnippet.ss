<% if $Posts %>
	<ul>
	<% control $Posts %>
	<li><a href="$Link">$MenuTitle</a></li>
	<% end_control %>
	</ul>
<% else %>
	<h3><% _t('NOENTRIES', 'There are no blog entries') %></h3>
<% end_if %>