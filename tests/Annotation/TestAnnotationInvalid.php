<?php

namespace Reflection\Tests\Validator\Annotation;

use Reflection\Validator\MethodValidator;
use Reflection\Validator\Annotation\ReflectionValidatorMethodAnnotationInterface;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class TestAnnotationInvalid implements ReflectionValidatorMethodAnnotationInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(\ReflectionMethod $method)
    {
        new MethodValidator($method);
    }
}
