<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\Control\Director;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.5, Apr 9, 2018 - 8:41:54 PM
 */
class DOMSearchEngineExtension
        extends DataExtension {

    private static $allowed_actions = [
    ];

    public function onAfterInit() {
        Requirements::css("hudhaifas/silverstripe-dataobject-manager: res/css/dataobject.css");
        Requirements::css("hudhaifas/silverstripe-dataobject-searcher: res/css/dataresult.css");

        if ($this->owner->isRTL()) {
            Requirements::css("hudhaifas/silverstripe-dataobject-manager: res/css/dataobject-rtl.css");
        }

        Requirements::javascript("hudhaifas/silverstripe-dataobject-manager: res/js/dataobject.manager.js");

        if (isset($_GET['Search'])) {
            $sanitized_search_text = filter_var($_GET['Search'], FILTER_SANITIZE_STRING);
            $this->DefaultSearchText = DBField::create_field(
                            'HTMLText', $sanitized_search_text
            );
        }
    }

    /// Search
    public function SearchLink($action = null) {
        $page = $this->owner->getSearchPage();

        return $page ? Director::absoluteURL($page->Link($action)) : null;
    }

}