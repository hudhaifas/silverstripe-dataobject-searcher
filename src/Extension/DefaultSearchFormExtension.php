<?php

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.5, Apr 9, 2018 - 8:41:54 PM
 */
class DefaultSearchFormExtension
        extends DataExtension {

    public function getDefaultSearchForm() {
        if ($page = DefaultSearchPage::get()->first()) {
            $form = new Form(
                    $this->owner, //
                    'DefaultSearchForm', //
                    new FieldList(new TextField('q', '')), //
                    new FieldList(new FormAction('doSearch', 'Go'))
            );

            $form->setFormMethod('GET');
            $form->setFormAction($page->Link());
            $form->disableSecurityToken();
            $form->loadDataFrom($_GET);

            return $form;
        }
    }

}
