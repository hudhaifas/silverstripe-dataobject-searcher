<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\ORM\DataObject;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 9:59:11 PM
 */
class DOMCrawled
        extends DataObject {

    private static $table_name = 'DOMCrawled';
    private static $db = [
        'RecordID' => 'Int',
        'RecordClass' => 'Varchar(255)',
    ];
    private static $indexes = [
        'ObjectUniquePerCrawl' => [
            'type' => 'unique',
            'columns' => ['RecordID', 'RecordClass'],
        ]
    ];

    public function getRecordObject() {
        return DataObject::get($this->RecordClass)->byID($this->RecordID);
    }

}
