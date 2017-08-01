<?php

namespace Reflection\Tests\Validator\Annotation;

use PHPUnit\Framework\TestCase;
use Reflection\Validator\Annotation\ReflectionValidatorAnnotationReader;

class ReflectionValidatorAnnotationReaderTest extends TestCase
{
    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testAnnotationInvalid()
    {
        $this->expectExceptionMessage(
            'The "Reflection\Tests\Validator\Annotation\TestClass::' .
            'testMethodAnnotationInvalid()" method must have 0 arguments, 1 given.'
        );

        $reader = new ReflectionValidatorAnnotationReader();

        $this->assertInstanceOf(TestAnnotationInvalid::class, $reader->getMethodAnnotation(
            new \ReflectionMethod(TestClass::class, 'testMethodAnnotationInvalid'),
            TestAnnotationInvalid::class
        ));
    }

    public function testAnnotationValid()
    {
        $reader = new ReflectionValidatorAnnotationReader();

        $this->assertInstanceOf(TestAnnotationValid::class, $reader->getMethodAnnotation(
            new \ReflectionMethod(TestClass::class, 'testMethodAnnotationValid'),
            TestAnnotationValid::class
        ));
    }
}
