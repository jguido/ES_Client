<?php

require __DIR__.'/../vendor/autoload.php';

//require __DIR__.'/Tools/TestHelper.php';
//require __DIR__.'/Tools/GuzzleClientTest.php';


// Bootstrap the JMS custom annotations for Object to Json mapping
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    __DIR__.'/../vendor/jms/serializer/src'
);
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
