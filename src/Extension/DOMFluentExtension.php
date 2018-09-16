<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\i18n\i18n;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use TractorCow\Fluent\Model\Locale;
use TractorCow\Fluent\State\FluentState;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 15, 2018 - 7:20:32 PM
 */
class DOMFluentExtension
        extends DataExtension {

    public function getAllLocalized() {
        if (!class_exists('TractorCow\Fluent\State\FluentState') || !$this->owner->hasExtension('TractorCow\Fluent\Extension\FluentExtension')) {
            return new ArrayList([$this->owner]);
        }

        $list = new ArrayList([]);

        foreach (Locale::getCached() as $locale) {
            $list->add($this->owner->getLocalized($locale->Locale));
        }

        return $list;
    }

    public function getLocalized($locale) {
        return FluentState::singleton()->withState(function (FluentState $newState) use ($locale) {
                    $newState->setLocale($locale);

                    return i18n::with_locale($locale,
                                    function () use($locale) {

                                // Non-db records fall back to internal behaviour
                                if (!$this->owner->isInDB()) {
                                    return $this->owner;
                                }

                                // Reload this record in the correct locale
                                $record = DataObject::get($this->owner->ClassName)->byID($this->owner->ID);

                                return $record;
                            });
                });
    }

    public function getAllLocalizedContent() {
        if (!class_exists('TractorCow\Fluent\State\FluentState') || !$this->owner->hasExtension('TractorCow\Fluent\Extension\FluentExtension')) {
            return $this->owner->getSearchableContent();
        }

        $content = " ";

        foreach (Locale::getCached() as $locale) {
            $content .= " " . $this->owner->getLocalizedContent($locale->Locale);
        }

        return $content;
    }

    public function getLocalizedContent($locale) {
        return FluentState::singleton()->withState(function (FluentState $newState) use ($locale) {
                    $newState->setLocale($locale);

                    return i18n::with_locale($locale,
                                    function () use($locale) {

                                // Non-db records fall back to internal behaviour
                                if (!$this->owner->isInDB()) {
                                    return $this->owner;
                                }

                                // Reload this record in the correct locale
                                $record = DataObject::get($this->owner->ClassName)->byID($this->owner->ID);

                                return $record->getSearchableContent();
                            });
                });
    }

}
