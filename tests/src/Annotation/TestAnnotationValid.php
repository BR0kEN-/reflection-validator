<?php

namespace Reflection\Tests\Validator\Annotation;

use Reflection\Validator\MethodValidator;
use Reflection\Validator\ArgumentSpecification;
use Reflection\Validator\Annotation\ReflectionValidatorMethodAnnotationInterface;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class TestAnnotationValid implements ReflectionValidatorMethodAnnotationInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(\ReflectionMethod $method)
    {
        (new MethodValidator($method))
            ->setReturnType('int')
            ->setReturnByReference(false)
            ->addArgument(
                (new ArgumentSpecification('a'))
                    ->setType('int')
                    ->setOptional(false)
                    ->setPassedByReference(false)
            );
    }
}
