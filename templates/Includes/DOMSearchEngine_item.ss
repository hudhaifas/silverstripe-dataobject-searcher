<div class="" style="height: auto;">
    <div class="rh">
        <div>
            <a href="$LiveRecordLink" title="$LiveRecordTitle">
                <h3>$LiveRecordTitle</h3>
                <cite class="rh-url">$LiveRecordLink</cite>
            </a>
        </div>
    </div>

    <div class="rb">
        <% if $RecordImage %>
            <div class="rb-image">
                <a href="$LiveRecordLink" title="$LiveRecordTitle">
                    <img src="$RecordImage.Fit(64, 64).URL" alt="image" class="img-responsive" />
                </a>
            </div>

            <div class="rb-content-with-image">
                <span>
                    <p>$LiveRecordDescription.LimitWordCount(50).Plain</p>
                </span>
            </div>		
        <% else %>
            <div class="rb-content ">
                <span>
                    <p>$LiveRecordDescription.LimitWordCount(50).Plain</p>
                </span>
            </div>		
        <% end_if %>
    </div>
</div>
