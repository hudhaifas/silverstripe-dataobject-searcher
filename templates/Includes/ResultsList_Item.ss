<div class="row" style="height: auto;">
    <% if $RecordImage %>
        <div class="thumbnail text-center col-sm-1 col-xs-1">
            <a href="$RecordLink" title="$RecordTitle" target="_blank">
                <img src="$RecordImage.Square(64).URL" alt="image" class="img-responsive" />
            </a>
        </div>
        <div class="col-sm-11 col-xs-11">
            <% include ResultsList_Content %>
        </div>		
    <% else %>
        <div class="col-sm-12 col-xs-12 col-md-12">
            <% include ResultsList_Content %>
        </div>		
    <% end_if %>
</div>