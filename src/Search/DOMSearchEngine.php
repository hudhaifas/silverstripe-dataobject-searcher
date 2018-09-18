<?php

namespace HudhaifaS\DOM\Search;

use nglasl\extensible\CustomSearchEngine;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Convert;
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
        // hack to get the current request object
        $request = Controller::curr()->getRequest();

        $keywords = $this->escapeString($data['Search']);

        if (!$keywords) {
            return;
        }

        $start = $request->getVar('start') ? (int) $request->getVar('start') : 0;
        $pageLength = $resultPage->ResultsPerPage;

        $booleanSearch = strpos($keywords, '"') !== false ||
                strpos($keywords, '+') !== false ||
                strpos($keywords, '-') !== false ||
                strpos($keywords, '*') !== false;

        $startTime = microtime(true);

        $list = $this->searchEngine($keywords, $start, $pageLength, "\"Relevance\" DESC", "", $booleanSearch);

        $searchTime = round(microtime(true) - $startTime, 5);

        return [
            'Query' => $form->getSearchQuery(),
            'Results' => $list,
            'Time' => $searchTime,
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
    private function searchEngine($keywords, $start, $pageLength, $sortBy = "Relevance DESC", $booleanSearch = false) {
        $boolean = '';
        if ($booleanSearch) {
            $boolean = "IN BOOLEAN MODE";
        }

        $match = "MATCH (RecordTitle, RecordContent) AGAINST ('$keywords' $boolean)";

        // We make the relevance search by converting a boolean mode search into a normal one
        $relevanceKeywords = str_replace(array('*', '+', '-'), '', $keywords);
        $relevance = "MATCH (RecordTitle, RecordContent) AGAINST ('$relevanceKeywords') ";

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
            'RecordRank',
            "Relevance" => $relevance
        ];

        $query = $list->dataQuery()->query();
        $query->setFrom($sqlTable);
        $query->setSelect($select);
        $query->setOrderBy($sortBy);
        $query->setLimit($pageLength, $start);

        $totalCount = $query->unlimitedRowCount();

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
        return Convert::raw2sql($value);
    }

}
