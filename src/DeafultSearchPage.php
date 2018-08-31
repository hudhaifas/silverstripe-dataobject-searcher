<?php

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
