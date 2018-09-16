<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 11:40:33 PM
 */
class DOMIndexerTask
        extends BuildTask {

    private static $segment = 'DOMIndexerTask';
    protected $title = 'Index all craled content';
    protected $description = 'Index data collected by the Crawler task';

    public function run($request) {
        $crawledObjects = DOMCrawled::get();
        foreach ($crawledObjects as $crawled) {
            $indexed = $this->getIndexed($crawled);
            $this->indexObject($crawled->getRecordObject(), $indexed);

            $crawled->delete();
        }
    }

    public function indexObject($object, $indexed) {
        $indexed->RecordID = $object->ID;
        $indexed->RecordClass = $object->ClassName;

        $indexed->RecordLink = $object->getObjectLink();
        $indexed->RecordTitle = $object->getObjectTitle();
        $indexed->RecordDescription = DOMSearchHelper::remove_newlines($object->getObjectDescription());
        $indexed->RecordContent = DOMSearchHelper::remove_newlines($object->getAllLocalizedContent());
        $image = $object->getObjectImage();
        if ($image) {
            $indexed->RecordImageID = $object->getObjectImage()->ID;
        }

        $indexed->write();
    }

    public function getIndexed($crawled) {
        $indexed = DataObject::get(DOMIndexed::class)->filter([
                    'RecordID' => $crawled->RecordID,
                    'RecordClass' => $crawled->RecordClass,
                ])->first();

        if (!$indexed) {
            $indexed = new DOMIndexed();
            $indexed->RecordLink = $crawled->RecordLink;
        }

        return $indexed;
    }

}
