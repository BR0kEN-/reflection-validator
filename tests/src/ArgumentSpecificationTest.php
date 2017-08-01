<?php

namespace Reflection\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Reflection\Validator\ArgumentSpecification;

class ArgumentSpecificationTest extends TestCase
{
    use Data;

    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testWrongName()
    {
        $this->expectExceptionMessage(
            'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
            'reflectionMethod()" method has the "string" name, but must be "string1".'
        );

        (new ArgumentSpecification('string1'))
            ->setType('string')
            ->setOptional(false)
            ->setPassedByReference(false)
            ->validate((new \ReflectionMethod($this, 'reflectionMethod'))->getParameters()[0]);
    }

    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testWithoutType()
    {
        $this->expectExceptionMessage(
            'The type of the argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
            'reflectionMethod()" method is not specified.'
        );

        (new ArgumentSpecification('string'))
            ->setOptional(false)
            ->setPassedByReference(false)
            ->validate((new \ReflectionMethod($this, 'reflectionMethod'))->getParameters()[0]);
    }

    /**
     * @dataProvider providerOptional
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testOptional(bool $state, string $method, string $expectedExceptionMessage)
    {
        $this->expectExceptionMessage($expectedExceptionMessage);

        (new ArgumentSpecification('string'))
            ->setType('string')
            ->setOptional($state)
            ->setPassedByReference(false)
            ->validate((new \ReflectionMethod($this, $method))->getParameters()[0]);
    }

    /**
     * @dataProvider providerReference
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testReference(bool $state, string $method, string $expectedExceptionMessage)
    {
        $this->expectExceptionMessage($expectedExceptionMessage);

        (new ArgumentSpecification('string'))
          ->setType('string')
          ->setOptional(false)
          ->setPassedByReference($state)
          ->validate((new \ReflectionMethod($this, $method))->getParameters()[0]);
    }

    /**
     * @dataProvider providerType
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testType(string $type, string $method, string $expectedExceptionMessage)
    {
        $this->expectExceptionMessage($expectedExceptionMessage);

        (new ArgumentSpecification('string'))
          ->setType($type)
          ->setOptional(false)
          ->setPassedByReference(false)
          ->validate((new \ReflectionMethod($this, $method))->getParameters()[0]);
    }

    public function providerOptional()
    {
        return [
            [
                false,
                'reflectionMethodArgumentOptional',
                'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethodArgumentOptional()" method must not be optional.',
            ],
            [
                true,
                'reflectionMethod',
                'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethod()" method must be optional.',
            ],
        ];
    }

    public function providerReference()
    {
        return [
            [
                false,
                'reflectionMethodArgumentReference',
                'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethodArgumentReference()" method must not be passed by reference.',
            ],
            [
                true,
                'reflectionMethod',
                'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethod()" method must be passed by reference.',
            ],
        ];
    }

    public function providerType()
    {
        return [
            [
                'string',
                'reflectionMethodArgumentWithoutType',
                'The type of the argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethodArgumentWithoutType()" method must be of "string" type, none given.',
            ],
            [
                'string1',
                'reflectionMethod',
                'The argument 1 of the "Reflection\Tests\Validator\ArgumentSpecificationTest::' .
                'reflectionMethod()" method must be of "string1" type, "string" given.',
            ],
        ];
    }
}
