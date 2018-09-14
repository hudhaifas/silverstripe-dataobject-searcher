<?php

namespace HudhaifaS\DOM\Search;

use DataObjectPage;
use nglasl\extensible\CustomSearchEngine;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 10:28:51 AM
 */
class DOMSearchEngine
        extends CustomSearchEngine {

    public function getSearchResults($data = null, $form = null, $resultPage = null) {
        $results = ArrayList::create([]);

        if ($data['Search']) {
            $results = DataObject::get(DOMIndexed::class)->filterAny([
                'RecordTitle:PartialMatch' => $data['Search'],
                'RecordContent:PartialMatch' => $data['Search'],
            ]);
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
