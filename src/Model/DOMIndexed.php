<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 10:00:43 PM
 */
class DOMIndexed
        extends DataObject {

    private static $table_name = 'DOMIndexed';
    private static $db = [
        'RecordID' => 'Int',
        'RecordClass' => 'Varchar(255)',
        'RecordContent' => 'Text',
        'RecordLink' => 'Varchar(255)',
        'RecordTitle' => 'Varchar(255)',
        'RecordImageID' => 'Int',
        'RecordBreadcrumb' => 'Varchar(255)',
        'RecordDescription' => 'Text',
        'RecordKeywords' => 'Text',
        'RecordRank' => 'Int',
    ];
    private static $indexes = [
        'ObjectUniquePerIndex' => [
            'type' => 'unique',
            'columns' => ['RecordID', 'RecordClass'],
        ],
        'ObjectFulltextPerIndex' => [
            'type' => 'fulltext',
            'columns' => ['RecordTitle', 'RecordContent'],
        ]
    ];
    private static $create_table_options = [
        'MySQLDatabase' => 'ENGINE=MyISAM'
    ];
    private static $default_sort = 'RecordRank DESC';
    private static $summary_fields = [
        'RecordTitle',
        'RecordLink',
        'RecordRank',
    ];

    public function getObject() {
        return DataObject::get($this->RecordClass)->byID($this->RecordID);
    }

    public function CanPublicView() {
        return $this->getObject()->CanPublicView();
    }

    public function getRecordImage() {
        if ($this->RecordImageID) {
            return DataObject::get(Image::class)->byID($this->RecordImageID);
        }
    }

    public function increaseRank() {
        $this->RecordRank += 1;
        $this->write();
    }

}
