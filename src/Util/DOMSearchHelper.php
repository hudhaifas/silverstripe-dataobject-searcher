<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\Core\Convert;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 14, 2018 - 11:41:21 AM
 */
class DOMSearchHelper {

    public static function validateIndex($indexed) {
        if (!$indexed->getObject()) {
            $indexed->delete();

            return false;
        }

        return true;
    }

    public static function remove_newlines($string) {
        $string = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
        $string = Convert::html2raw($string);

        return trim($string);
    }

}
