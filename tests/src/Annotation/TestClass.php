<?php

namespace Reflection\Tests\Validator\Annotation;

class TestClass
{
    /**
     * @TestAnnotationInvalid
     *
     * @param int $a
     *   A number to add 42.
     *
     * @return int
     *   Resulting number.
     */
    public function testMethodAnnotationInvalid(int $a): int
    {
        return $a + 42;
    }

    /**
     * @TestAnnotationValid
     *
     * @param int $a
     *   A number to add 41.
     *
     * @return int
     *   Resulting number.
     */
    public function testMethodAnnotationValid(int $a): int
    {
        return $a + 41;
    }
}
