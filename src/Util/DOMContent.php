<?php

namespace HudhaifaS\DOM\Search;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 16, 2018 - 8:08:32 AM
 */
class DOMContent {

    protected $content;

    public function __construct() {
        $this->content = [];
    }

    public function push($text) {
        $this->content[] = $text;
    }

    public function content() {
        return implode(" ", $this->content);
    }

}
