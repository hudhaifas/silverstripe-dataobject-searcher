<div class="container dataobject-page $ExtraClasses">
    <div class="dataobject-page-header">
        <div class="row" style="margin-bottom: 1.5em;">
            <div class="col-md-12">
                $SearchForm
            </div>

            <div class="col-md-12">
                <sub><%t DOMSearchEngine.SEARCH_RESULTS_COUNT 'About {value} results' value=$Results.TotalItems%></sub>
            </div>
        </div>
    </div>

    <div class="dataobject-page-content">
        <div class="row">
            <div class="col-md-12">
                <% if Results.Count %>
                    <div class="row dataobject-grid dataobject-results">
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
                        <p><%t DOMSearchEngine.SEARCH_NO_RESULTS 'Sorry, your search query did not return any results.' %></p>
                    </div>
                <% end_if %>
            </div>
        </div>

        <% if Results.MoreThanOnePage %>
            <div class="">
                <div class="col-md-12">
                    <% with $Results %>
                        <% include List_Paginate %>
                    <% end_with %>
                </div>
            </div>
        <% end_if %>
    </div>
</div>