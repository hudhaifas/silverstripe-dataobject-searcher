<form class="form-inline" $AttributesHTML role="search">
    <p id="Form_DefaultSearchForm_error" class="message " style="display: none"></p>
    
    <fieldset>
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="Search" id="Form_DefaultSearchForm_Search" class="form-control" placeholder="<%t DataObjectPage.SEARCH 'Search' %>" value="$DefaultSearchText" />

                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default" name="action_doSearch" id="Form_DefaultSearchForm_action_doSearch">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </fieldset>
</form>