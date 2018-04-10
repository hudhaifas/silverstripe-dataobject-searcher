<div class="container dataobject-page $ExtraClasses">
    <div class="dataobject-page-header">
        <div class="row" style="margin-bottom: 1.5em;">
            <div class="col-md-12">
                $DefaultSearchForm
            </div>

            <div class="col-md-12">
                <sub><%t DataObjectPage.SEARCH_RESULTS_COUNT 'About {value} results' value=$Results.Count%></sub>
            </div>
        </div>
    </div>

    <div class="dataobject-page-content">
        <div class="row">
            <div class="col-md-12">
                <% include ResultsList %>
            </div>
        </div>

        <% if Results  %>
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