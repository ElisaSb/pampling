<?php

declare(strict_types=1);

class test
{

    public function test()
    {
        $classLoader = new ClassLoader('Doctrine', CORE_PATH . 'external/');
        $classLoader->register();

        AnnotationRegistry::registerFile(CORE_PATH.'external/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

        $cache = new ArrayCache();

        $annotationReader = new AnnotationReader();

        $cacheReader = new CachedReader(
            $annotationReader,
            $cache
        );

        $driverChain = new DriverChain();

        $annotationDriver = new AnnotationDriver(
            $annotationReader,
            array(CORE_PATH . 'model/')
        );

        $driverChain->addDriver($annotationDriver, 'gallib\core\model');

        $doctrineConfig = new Configuration();

        $doctrineConfig->setProxyDir(CORE_PATH . 'model/proxy');
        $doctrineConfig->setProxyNamespace('gallib\core\model\proxy');
        $doctrineConfig->setAutoGenerateProxyClasses(true);
        $doctrineConfig->setMetadataDriverImpl($driverChain);
        $doctrineConfig->setMetadataCacheImpl($cache);
        $doctrineConfig->setQueryCacheImpl($cache);

        $database = $this->config->database;

        $connectionOptions = array(
            'driver'   => $database['driver'],
            'dbname'   => $database['dbname'],
            'user'     => $database['username'],
            'password' => $database['password']
        );

        $this->em = EntityManager::create($connectionOptions, $doctrineConfig);
    }

}