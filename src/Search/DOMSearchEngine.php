<?php

namespace HudhaifaS\DOM\Search;

use DataObjectPage;
use nglasl\extensible\CustomSearchEngine;
use SilverStripe\CMS\Controllers\ModelAsController;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 10:28:51 AM
 */
class DOMSearchEngine
        extends CustomSearchEngine {

    public function getSearchResults($data = null, $form = null, $resultPage = null) {
        $pages = DataObjectPage::get();
        $results = ArrayList::create(array());

        if ($data['Search']) {
            foreach ($pages as $page) {
                $controller = ModelAsController::controller_for($page);
                if ($controller->isSearchable()) {
                    $result = $controller->getObjectsList();
                    $results->merge($controller->searchObjects($result, $data['Search']));
                }
            }
        }

        $start = isset($data['start']) ? (int) $data['start'] : 0;
        $size = $resultPage->ResultsPerPage;
        $count = $results->count();

        $paginated = PaginatedList::create(
                        $results
                )
                ->setPageLength($size)
                ->setPageStart($start)
                ->setTotalItems($count);

        return [
            'Title' => _t('DOMSearchEngine.SEARCH_RESULTS', 'Search Results'),
            'Query' => $form->getSearchQuery(),
            'Results' => $paginated,
            'Count' => $count
        ];
    }

    public function getSelectableFields($page = null) {
        return [
            'NumView' => 'NumView',
        ];
    }

}
