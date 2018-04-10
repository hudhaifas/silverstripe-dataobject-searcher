<?php

/*
 * MIT License
 *  
 * Copyright (c) 2018 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.5, Apr 9, 2018 - 8:39:32 PM
 */
class DefaultSearchPage
        extends Page {

    public function requireDefaultRecords() {
        if (DefaultSearchPage::get()->count() < 1) {
            $search = new DefaultSearchPage();
            $search->Title = "Search results";
            $search->MenuTitle = "Search";
            $search->ShowInMenus = 0;
            $search->ShowInSearch = 0;
            $search->URLSegment = "search";
            $search->write();

            $search->doPublish('Stage', 'Live');
        }
    }

    public function MetaTags($includeTitle = true) {
        $tags = parent::MetaTags($includeTitle);
        $tags .= '<meta name="robots" content="noindex">';
        return $tags;
    }

}

/**
 * @package googlesitesearch
 */
class DefaultSearchPage_Controller
        extends Page_Controller {

    public function init() {
        parent::init();

        Requirements::css(DATAOBJECT_MANAGER_DIR . "/css/dataobject.css");
        Requirements::css(DATAOBJECT_SEARCHER_DIR . "/css/dataresult.css");

        if ($this->isRTL()) {
            Requirements::css(DATAOBJECT_MANAGER_DIR . "/css/dataobject-rtl.css");
        }

        Requirements::javascript(DATAOBJECT_MANAGER_DIR . "/js/dataobject.manager.js");

        if (isset($_GET['Search'])) {
            $sanitized_search_text = filter_var($_GET['Search'], FILTER_SANITIZE_STRING);
            $this->DefaultSearchText = DBField::create_field(
                            'HTMLText', $sanitized_search_text
            );
        }
    }

    public function index(SS_HTTPRequest $request) {
        $start = microtime(true); // time in Microseconds

        $pages = DataObjectPage::get();
        $results = ArrayList::create(array());

        if ($query = $request->getVar('Search')) {
            foreach ($pages as $page) {
                $controller = ModelAsController::controller_for($page);
                if ($controller->isSearchable()) {
                    $result = $controller->getObjectsList();
                    $results->merge($controller->searchObjects($result, $query));
                }
            }
        }

        if (!$results) {
            return array();
        }

        $paginated = PaginatedList::create(
                        $results, $request
                )->setPageLength(36)
                ->setPaginationGetVar('s');

        $end = microtime(true); // time in Microseconds

        $data = array(
            'Results' => $paginated,
            'Seconds' => ($end - $start) / 1000
        );

        if ($request->isAjax()) {
            return $this->customise($data)
                            ->renderWith('ObjectsList');
        }

        return $data;
    }

}
