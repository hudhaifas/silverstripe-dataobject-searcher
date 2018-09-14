<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

}
