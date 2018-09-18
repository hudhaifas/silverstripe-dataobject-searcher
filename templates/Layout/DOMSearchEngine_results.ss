<div class="container dataobject-page $ExtraClasses">
    <div class="dataobject-page-header">
        <div class="row" style="margin-bottom: 1.5em;">
            <div class="col-md-12">
                $SearchForm
            </div>

            <div class="col-md-12">
                <sub><%t DOMSearchEngine.SEARCH_RESULTS 'About {value} results ({time} seconds)' value=$Results.TotalItems time=$Time %></sub>
            </div>
        </div>
    </div>

    <div class="dataobject-page-content">
        <div>
            <% if Results.Count %>
                <div class="dataobject-grid dataobject-results">
                    <% loop $Results %>
                        <% if CanPublicView %>
                            <div class="col-sm-9 dataobject-item">
                                <% include DOMSearchEngine_item %>
                            </div>
                        <% end_if %>
                    <% end_loop %>
                </div>

            <% else %>
                <div class="">
                    <p><%t DOMSearchEngine.SEARCH_NO_RESULTS 'Sorry, your search query did not return any results.' %></p>
                </div>
            <% end_if %>
        </div>

        <% if Results.MoreThanOnePage %>
            <div>
                <% with $Results %>
                    <% include List_Paginate %>
                <% end_with %>
            </div>
        <% end_if %>
    </div>
</div>