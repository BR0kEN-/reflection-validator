<?php

namespace Reflection\Validator\Annotation;

/**
 * @see ReflectionValidatorAnnotationReader
 */
interface ReflectionValidatorMethodAnnotationInterface
{
    /**
     * Validate a method reflecting the annotation.
     *
     * @param \ReflectionMethod $method
     *   A method to validate.
     *
     * @throws \Exception
     *   When a method is not valid.
     */
    public function validate(\ReflectionMethod $method);
}
