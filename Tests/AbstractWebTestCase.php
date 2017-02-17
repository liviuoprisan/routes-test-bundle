<?php

namespace Oldev\Bundle\RoutesTestBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * @author Liviu Oprisan <liviu.oprisan@orange.com>
 * @author Samuel Chiriluta <samuel.chiriluta@orange.com>
 */
abstract class AbstractWebTestCase extends WebTestCase {

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected static $doctrine;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected static $container;

    /**
     * @var bool
     */
    protected static $setupDone = false;

    /**
     * @var \Doctrine\ORM\EntityManage
     */
    protected static $entityManager;

    /**
     * @var bool
     */
    protected static $isJenkins = false;

    public static function setUpBeforeClass()
    {
        if (!static::$setupDone)
        {
            static::$kernel = static::createKernel();
            static::$kernel->boot();
            static::$container = static::$kernel->getContainer();
            static::$doctrine = static::$container->get('doctrine');
            static::$entityManager = static::$doctrine->getManager();
            static::$setupDone = true;
            static::$isJenkins = static::isJenkins();
        }
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public static function getDoctrine()
    {
        return self::$doctrine;
    }

    /**
     * @return \Doctrine\ORM\EntityManage
     */
    public static function getEntityManager()
    {
        return self::$entityManager;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public static function getContainer()
    {
        return static::$container;
    }

    protected function tearDown()
    {
        $this->getEntityManager()->clear();
//        parent::tearDown();
    }

    /**
     * 
     * @return Client
     */
    public function doLogin()
    {
        $client = static::createClient(array(), array(
                    'PHP_AUTH_USER' => 'admin',
                    'PHP_AUTH_PW' => 'admin',
        ));
        $client->followRedirects(true);
        return $client;
    }

    public static function getUniqueIdFromForm($form)
    {
        $uri = $form->getUri();
        $uri_parts = parse_url($uri);
        $query_parts = array();
        parse_str($uri_parts['query'], $query_parts);
        $uniqid = $query_parts['uniqid'];
        return $uniqid;
    }

    public static function checkAndDeleteTestData($data, $entity, $property)
    {
        $em = self::getEntityManager();
        $entit = $em->getRepository($entity)->findOneBy(array($property => $data));
        if ($entit)
        {
            $em->remove($entit);
            $em->flush();
        }
        unset($entit);
    }

    /**
     * Used for skipping some tests if we are on Jenking environment
     * 
     * @return boolean
     */
    public static function isJenkins()
    {
        if (isset($_SERVER['HOME']) && strpos($_SERVER['HOME'], 'jenkins') !== false)
        {
            return true;
        }

        return false;
    }

    public static function tearDownAfterClass()
    {
        echo "\n finished " . get_called_class() . "\n";
    }

}
