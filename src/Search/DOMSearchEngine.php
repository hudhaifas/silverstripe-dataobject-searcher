<?php

namespace HudhaifaS\DOM\Search;

use nglasl\extensible\CustomSearchEngine;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
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

        // hack to get the current request object
        $request = Controller::curr()->getRequest();

        $keywords = $this->escapeString($data['Search']);

        if (!$keywords) {
            return;
        }

        $start = $request->getVar('start') ? (int) $request->getVar('start') : 0;
        $pageLength = $resultPage->ResultsPerPage;
        $count = $results->count();

        $list = $this->searchEngine($keywords, $start, $pageLength);

        return [
            'Query' => $form->getSearchQuery(),
            'Results' => $list,
            'Count' => $count
        ];
    }

    /**
     * Inherited the fulltext search implemented in MySQLDatabase.searchEngine
     * 
     * @param string $keywords Keywords as a string.
     * @param int $start
     * @param type $pageLength
     * @param type $sortBy
     * @param type $booleanSearch
     * @return PaginatedList
     */
    private function searchEngine($keywords, $start, $pageLength, $sortBy = "RecordRank DESC", $booleanSearch = false) {
        $boolean = '';
        if ($booleanSearch) {
            $boolean = "IN BOOLEAN MODE";
        }

        $match = "MATCH (RecordTitle, RecordContent) AGAINST ('$keywords' $boolean)";

        $list = DataList::create(DOMIndexed::class)->where($match);
        $sqlTable = '"' . DataObject::getSchema()->tableName(DOMIndexed::class) . '"';

        $select = [
            'RecordID',
            'RecordClass',
            'RecordContent',
            'RecordLink',
            'RecordTitle',
            'RecordImageID',
            'RecordBreadcrumb',
            'RecordDescription',
            'RecordKeywords',
            'RecordRank'
        ];

        $query = $list->dataQuery()->query();
        $query->setFrom($sqlTable);
        $query->setSelect($select);

        $totalCount = $query->unlimitedRowCount();
        
        $query->setOrderBy($sortBy);
        $query->setLimit($pageLength, $start);

        $records = $query->execute();

        $objects = [];

        foreach ($records as $record) {
            $objects[] = new DOMIndexed($record);
        }

        $result = new PaginatedList(new ArrayList($objects));
        $result->setPageStart($start);
        $result->setPageLength($pageLength);
        $result->setTotalItems($totalCount);

        // The list has already been limited by the query above
        $result->setLimitItems(false);

        return $result;
    }

    public function getSelectableFields($page = null) {
        return [
            'NumView' => 'NumView',
        ];
    }

    public function escapeString($value) {
        return $value;
    }

}
