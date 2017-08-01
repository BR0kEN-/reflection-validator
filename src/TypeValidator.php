<?php

namespace Reflection\Validator;

trait TypeValidator
{
    protected function isTypeValid(\ReflectionType $type, string $compare)
    {
        $string = (string) $type;

        return $type->isBuiltin() ? $string === $compare : is_a($string, $compare, true);
    }
}
