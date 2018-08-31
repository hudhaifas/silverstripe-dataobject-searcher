<?php

use SilverStripe\CMS\Controllers\ModelAsController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\Requirements;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.5, Apr 9, 2018 - 8:39:32 PM
 */
class DefaultSearchPageController
        extends PageController {

    public function init() {
        parent::init();

        Requirements::css("hudhaifas/silverstripe-dataobject-manager: res/css/dataobject.css");
        Requirements::css("hudhaifas/silverstripe-dataobject-searcher: res/css/dataresult.css");

        if ($this->isRTL()) {
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

    public function index(HTTPRequest $request) {
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
