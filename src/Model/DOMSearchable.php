<?php

namespace HudhaifaS\DOM\Search;

use HudhaifaS\DOM\Model\DiscoverableDataObject;
use HudhaifaS\DOM\Model\ManageableDataObject;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 11, 2018 - 10:44:32 PM
 */
interface DOMSearchable extends ManageableDataObject, DiscoverableDataObject {

    /**
     * Prepare the content that will be indexed and used by the internal search engine.
     */
    public function getSearchableContent();
}
