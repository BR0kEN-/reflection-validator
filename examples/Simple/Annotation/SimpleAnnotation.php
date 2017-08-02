<?php

namespace Reflection\Examples\Validator\Simple\Annotation;

use Reflection\Validator\MethodValidator;
use Reflection\Validator\ArgumentSpecification;
use Reflection\Validator\Annotation\ReflectionValidatorMethodAnnotationInterface;
use Reflection\Examples\Validator\Simple\Component\SimpleComponent;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class SimpleAnnotation implements ReflectionValidatorMethodAnnotationInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(\ReflectionMethod $method)
    {
        // - The method must be a member of "SimpleComponent" or its children.
        // - The method must have 2 arguments (not less and not more, exactly 2).
        // - The first argument of the method must be of "array" type, passed
        //   by reference and with the "form" name.
        // - The second argument of the method must be of "\Iterator" type,
        //   not passed by reference and with the "formState" name.
        (new MethodValidator($method, SimpleComponent::class))
            ->addArgument(
                (new ArgumentSpecification('form'))
                    ->setType('array')
                    ->setOptional(false)
                    ->setPassedByReference(true)
            )
            ->addArgument(
                (new ArgumentSpecification('formState'))
                    ->setType(\Iterator::class)
                    ->setOptional(false)
                    ->setPassedByReference(false)
            );
    }
}
