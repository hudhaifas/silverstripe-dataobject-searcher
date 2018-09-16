<?php

namespace HudhaifaS\DOM\Search;

use ReflectionClass;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\DefaultAdminService;
use SilverStripe\Security\Member;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 11:31:56 PM
 */
class DOMCrawlerTask
        extends BuildTask {

    private static $admin_email = 'crawler';
    private static $segment = 'DOMCrawlerTask';
    protected $title = 'Crawl over all dataobjects';
    protected $description = 'Crawl over all dataobjects and collect content that can be used in search';

    public function run($request) {
        $service = DefaultAdminService::singleton();
        $member = $service->findOrCreateAdmin($this->config()->admin_email);

        Member::actAs($member, function() use($request) {
            $this->exec($request);
        });
    }

    public function exec($request) {
        $classes = $this->getSearchableClasses();

        foreach ($classes as $class) {
            $this->crawlClass($class);
        }
    }

    public function crawlClass($class) {
        $objects = DataObject::get($class);

        foreach ($objects as $object) {
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
