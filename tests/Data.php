<?php

namespace Reflection\Tests\Validator;

trait Data
{
    protected function emptyMethod()
    {
    }

    protected function &reflectionMethod(string $string, \Iterator $iterator): int
    {
    }

    protected function &reflectionMethodWithoutReturnType(string $string, \Iterator $iterator)
    {
    }

    protected function reflectionMethodArgumentOptional(string $string = null)
    {
    }

    protected function reflectionMethodArgumentReference(string &$string)
    {
    }

    protected function reflectionMethodArgumentWithoutType($string)
    {
    }
}
