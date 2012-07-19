<?php

namespace Controller;
use Europa\Controller\RestController;
use Testes\Coverage\Coverage;
use Testes\Finder\Finder;

/**
 * A default controller for a base implementation.
 * 
 * @category Controllers
 * @package  Europa
 * @author   Trey Shugart <treshugart@gmail.com>
 * @license  Copyright (c) 2011 Trey Shugart http://europaphp.org/license
 */
class Test extends RestController
{
    /**
     * Runs tests.
     * 
     * @param string $test         The test to run.
     * @param bool   $showUntested Whether or not to show untested lines of code.
     * 
     * @return void
     */
    public function cli($test = 'Test', $showUntested = false)
    {
        // start covering tests
        $analyzer = new Coverage;
        $analyzer->start();
        
        // run tests
        $suite = new Finder(__DIR__ . '/../Test/Test');
        $suite = $suite->run();
        
        // stop covering and analyze results
        $analyzer = $analyzer->stop();
        $analyzer->addDirectory(__DIR__ . '/../../../src/Europa');
        $analyzer->is('\.php$');
        
        return [
            'percent'      => round(number_format($analyzer->getPercentTested(), 2), 2),
            'suite'        => $suite,
            'report'       => $analyzer,
            'showUntested' => $showUntested
        ];
    }
}