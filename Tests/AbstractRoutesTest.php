<?php

namespace Oldev\Bundle\RoutesTestBundle\Tests;

use Oldev\Bundle\RoutesTestBundle\Tests\AbstractWebTestCase;

/**
 * @author Liviu Oprisan <liviu.oprisan@orange.com>
 * @author Samuel Chiriluta <samuel.chiriluta@orange.com>
 */
abstract class AbstractRoutesTest extends AbstractWebTestCase {

    private function getRoutes()
    {
        /** @var $router \Symfony\Component\Routing\Router */
        $router = $this->getContainer()->get('router');

        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();

        $allRoutes = $collection->all();

        return $allRoutes;
    }

    protected function runRoutesTest()
    {

        $routes = $this->getRoutes();

        $client = $this->doLogin();

        $number_of_get_routes = 0;
        $number_of_checked_routes = 0;
        $number_of_routes_that_are_ok = 0;
        $number_of_routes_that_are_not_ok = 0;
        $routes_that_dont_work = "";
        $ignoreThisRoutes = static::getExcludedRoutes();

        echo "\n";

        foreach ($routes as $route)
        {
            if (!$route->getMethods() || in_array('GET', $route->getMethods()))
            {
                $number_of_get_routes++;
            }

            if ((!$route->getMethods() || in_array('GET', $route->getMethods()) ) // is get method
                    && !in_array($route->getPath(), $ignoreThisRoutes) // is not on initial ignore list because of errors
            )
            {

                $routePath = $route->getPath();

                if (strstr($routePath, '{') !== false)
                {
                    $routeDefaults = $route->getDefaults();

                    foreach ($routeDefaults as $key => $value)
                    {
                        $routePath = str_replace('{' . $key . '}', $value, $routePath);
                    }

                    if (strstr($routePath, '{') !== false)
                    {
                        continue;
                    }
                }

                echo $routePath . "\n";

                flush();
                ob_flush();

                $number_of_checked_routes++;

                $client->request('GET', $routePath);

//                $response = $client->getResponse()->getStatusCode();
//                echo $route->getPath() . ': ' . $response . "\n";

                if ($client->getResponse()->isSuccessful())
                {
                    $number_of_routes_that_are_ok++;
                }
                else
                {
                    var_dump('This route FAILED: ' . $routePath);
                    $number_of_routes_that_are_not_ok++;
                    $routes_that_dont_work .= "'{$routePath}', ";
                }

                $succesfull = $client->getResponse()->isSuccessful();



                if (!$succesfull)
                {
                    echo '----------------------------------------------------------------------';
                    echo $client->getResponse()->getContent();
                    echo '----------------------------------------------------------------------';
                }

                $this->assertTrue($succesfull);
            }
        }

        $routes_count = count($routes);

        echo 'total routes: ' . $routes_count . "\n";
        echo 'number of get routes: ' . $number_of_get_routes . "\n";
        echo 'number of routes checked: ' . $number_of_checked_routes . "\n";
        echo 'number of routes ok: ' . $number_of_routes_that_are_ok . "\n";
        echo 'number of routes with error: ' . $number_of_routes_that_are_not_ok . "\n";

        if ($number_of_routes_that_are_not_ok > 0)
        {
            echo "Routes that don`t work: " . $routes_that_dont_work . "\n";
        }

        flush();
        ob_flush();
    }

    /**
     * return an array here with the routes that should be excluded (get routes that use parameters like "?a=2")
     * usually ajaxes, sonata export and sonata batch
     * @return array
     */
    abstract public function getExcludedRoutes();
}
