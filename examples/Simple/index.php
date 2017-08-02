<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Reflection\Examples\Validator\Simple\Component\SimpleComponent;
use Reflection\Examples\Validator\Simple\Annotation\SimpleAnnotation;
use Reflection\Validator\Annotation\ReflectionValidatorAnnotationReader;

AnnotationRegistry::registerLoader('class_exists');

$reader = new ReflectionValidatorAnnotationReader();
$reader->addNamespace('Reflection\Examples\Validator\Simple\Annotation');

$annotations = [];

foreach ((new ReflectionClass(SimpleComponent::class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
    $annotations[] = $reader->getMethodAnnotation($method, SimpleAnnotation::class);
}

var_dump($annotations);
