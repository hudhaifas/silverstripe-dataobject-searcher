<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\ORM\DataExtension;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 5:23:18 PM
 */
class DOMSearchSearchFormExtention
        extends DataExtension {

    public function updateExtensibleSearchSearchForm($form) {
        if ($form) {
            // Replace the search title with a placeholder.
            $search = $form->Fields()->dataFieldByName('Search');
            $search->setAttribute('placeholder', _t('DOMSearchEngine.SEARCH', 'Search'));
        }
    }

}
