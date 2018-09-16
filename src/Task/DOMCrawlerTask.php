<?php

namespace HudhaifaS\DOM\Search;

use ReflectionClass;
use SilverStripe\ORM\DataObject;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 11:31:56 PM
 */
class DOMCrawlerTask
        extends DOMTask {

    private static $segment = 'DOMCrawlerTask';
    protected $title = 'Crawl over all dataobjects';
    protected $description = 'Crawl over all dataobjects and collect content that can be used in search';

    public function exec($request) {
        $classes = $this->getSearchableClasses();
        $count = count($classes);

        $this->println("Crawling $count class(es)");
        foreach ($classes as $class) {
            $this->println("Crawling $class");
            $this->crawlClass($class);
        }
    }

    public function crawlClass($class) {
        $objects = DataObject::get($class);
        $count = $objects->count();

        $this->println("Crawling $count object(s)");

        foreach ($objects as $index => $object) {
            $this->printProgress($index, $count);
            $crawled = $this->getCrawled($object);
            $this->crawlObject($object, $crawled);
        }
    }

    public function crawlObject($object, $crawled) {
        if (!$object->getObjectLink()) {
            return;
        }

        $crawled->RecordID = $object->ID;
        $crawled->RecordClass = $object->ClassName;

        $crawled->write();
    }

    public function getCrawled($object) {
        $crawled = DataObject::get(DOMCrawled::class)->filter([
                    'RecordID' => $object->ID,
                    'RecordClass' => $object->ClassName,
                ])->first();

        if (!$crawled) {
            $crawled = new DOMCrawled();
            $crawled->RecordID = $object->ID;
            $crawled->RecordClass = $object->ClassName;
        }

        return $crawled;
    }

    public function getSearchableClasses() {
        $classes = get_declared_classes();
        $searchable = array();
        foreach ($classes as $clazz) {
            $reflect = new ReflectionClass($clazz);
            if ($reflect->implementsInterface(DOMSearchable::class)) {
                $searchable[] = $clazz;
            }
        }

        return $searchable;
    }

}
