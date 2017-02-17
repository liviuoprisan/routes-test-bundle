<?php

namespace YourProject\YourBundle\Tests;

use Oldev\Bundle\RoutesTestBundle\Tests\AbstractRoutesTest;

/**
 * @author Liviu Oprisan <liviu.oprisan@orange.com>
 */
class RoutesTestExample extends AbstractRoutesTest {

    /**
     * This is the test for all project routes, excepting the excluded ones, returned by getExcludedRoutes()
     */
    public function testRoutes()
    {
        parent::runRoutesTest();
    }

    /**
     * Write here the routes you want to not tests
     * The routes with parameters can also to be automatically tested, if there are default parameters defined
     * 
     * @return string
     */
    public function getExcludedRoutes()
    {
        $routesWithErrors = array(
            '/_wdt/{token}',
            '/_profiler/',
            '/_profiler/search',
            '/_profiler/search_bar',
            '/_profiler/purge',
            '/_profiler/info/{about}',
            '/_profiler/import',
            '/_profiler/export/{token}.txt',
            '/_profiler/phpinfo',
            '/_profiler/{token}/search/results',
            '/_profiler/{token}',
            '/_profiler/{token}/router',
            '/_profiler/{token}/exception',
            '/_profiler/{token}/exception.css',
            '/_configurator/',
            '/_configurator/step/{index}',
            '/_configurator/final',
            'etc',
        );

        return $routesWithErrors;
    }

}
