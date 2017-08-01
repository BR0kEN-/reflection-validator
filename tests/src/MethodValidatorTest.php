<?php

namespace Reflection\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Reflection\Validator\MethodValidator;

class MethodValidatorTest extends TestCase
{
    use Data;

    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function test()
    {
        $this->expectExceptionMessage(
            'The "Reflection\Tests\Validator\MethodValidatorTest::emptyMethod()" method defined in the ' .
            'class that not inherits "ReflectionMethod".'
        );

        new MethodValidator(new \ReflectionMethod($this, 'emptyMethod'), \ReflectionMethod::class);
    }
}
