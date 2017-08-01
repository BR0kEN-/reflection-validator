<?php

namespace Reflection\Validator\Annotation;

use Doctrine\Common\Annotations\SimpleAnnotationReader;

/**
 * Adds an ability to validate a method reflecting the annotation.
 */
class ReflectionValidatorAnnotationReader extends SimpleAnnotationReader
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     *   When one of the annotations treated as invalid.
     */
    public function getMethodAnnotations(\ReflectionMethod $method)
    {
        $annotations = [];

        foreach (parent::getMethodAnnotations($method) as $i => $annotation) {
            if ($annotation instanceof ReflectionValidatorMethodAnnotationInterface) {
                $annotation->validate($method);
            }

            $annotations[$i] = $annotation;
        }

        return $annotations;
    }
}
