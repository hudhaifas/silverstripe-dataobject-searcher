<?php

namespace HudhaifaS\DOM\Search;

use SilverStripe\Core\Environment;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\DefaultAdminService;
use SilverStripe\Security\Member;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Sep 7, 2018 - 11:31:56 PM
 */
abstract class DOMTask
        extends BuildTask {

    private static $admin_email = 'domcrawler';
    private $debug = false;

    public function run($request) {
        $service = DefaultAdminService::singleton();
        $member = $service->findOrCreateAdmin($this->config()->admin_email);
        $this->debug = $request->getVar('debug');

        // Set max time and memory limit
        Environment::increaseTimeLimitTo();
        Environment::increaseMemoryLimitTo();

        Member::actAs($member,
                function() use($request) {
            $startTime = microtime(true);

            $this->exec($request);

            $taskTime = gmdate("H:i:s", microtime(true) - $startTime);
            $this->println('');
            $this->println("Task is completed in $taskTime");
        });
    }

    public abstract function exec($request);

    protected function println($string_message = '') {
        return isset($_SERVER['SERVER_PROTOCOL']) ? print "$string_message<br />" . PHP_EOL :
                print $string_message . PHP_EOL;
    }

    protected function printProgress($index, $total) {
        if (!$this->debug) {
            return;
        }

        $bs = chr(8);
        $backspaces = '';

        $digitsCount = 0;
        if ($index > 0) {
            $digitsCount = strlen((string) $index);
            $digitsCount += strlen((string) $total);
            $digitsCount += 1;
        }
        for ($i = 0; $i < $digitsCount; $i++) {
            $backspaces .= $bs;
        }

        echo "{$backspaces}" . ($index + 1) . "/{$total}";
    }

}
