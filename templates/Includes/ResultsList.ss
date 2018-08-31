<% if Results  %>
    <div class="row dataobject-grid dataobject-results sdlfkjsdf">
        <% loop $Results %>
            <% if CanPublicView %>
                <div class="col-sm-9 dataobject-item">
                    <% include ResultsList_Item %>
                </div>
            <% end_if %>
        <% end_loop %>
    </div>

<% else %>
    <div class="row">
        <p><%t DataObjectPage.SEARCH_NO_RESULTS 'Sorry, your search query did not return any results.' %></p>
    </div>
<% end_if %>