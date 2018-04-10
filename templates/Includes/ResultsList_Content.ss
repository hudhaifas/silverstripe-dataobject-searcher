<h3><a href="$ObjectLink" title="$ObjectTitle" target="_blank">$ObjectTitle</a></h3>

<div>
    <cite class="dataobject-url">$ObjectLink</cite>
    <span class="dataobject-content">
        <% if $ObjectSummary.Count >= 0 %>
            <% loop $ObjectSummary %>
                <p><% if $Title %>$Title:<% end_if %> $Value.LimitWordCount(20).Plain</p>
            <% end_loop %>
        <% else %>
            $ObjectSummary
        <% end_if %>    
    </span>
</div>
