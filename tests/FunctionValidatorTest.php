<?php

namespace Reflection\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Reflection\Validator\FunctionValidator;
use Reflection\Validator\ArgumentSpecification;

class FunctionValidatorTest extends TestCase
{
    use Data;

    protected $firstArgument;
    protected $secondArgument;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setUpArguments();
    }

    protected function setUpArguments()
    {
        $this->firstArgument = (new ArgumentSpecification('string'))
            ->setOptional(false)
            ->setPassedByReference(false)
            ->setType('string');

        $this->secondArgument = (new ArgumentSpecification('iterator'))
            ->setOptional(false)
            ->setPassedByReference(false)
            ->setType(\Iterator::class);
    }

    public function testPositive()
    {
        $validator = (new FunctionValidator(new \ReflectionMethod($this, 'reflectionMethod')))
            ->setReturnByReference(true)
            ->setReturnType('int')
            ->addArgument($this->firstArgument)
            ->addArgument($this->secondArgument);

        $this->assertAttributeCount(2, 'arguments', $validator);
    }

    /**
     * @dataProvider providerWrongNumberOfArguments
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testWrongNumberOfArguments(array $arguments)
    {
        $count = count($arguments);
        $validator = (new FunctionValidator(new \ReflectionMethod($this, 'reflectionMethod')))
            ->setReturnByReference(true)
            ->setReturnType('int');

        foreach ($arguments as $argument) {
            $validator->addArgument($argument);
        }

        $this->assertAttributeCount($count, 'arguments', $validator);
        $this->expectExceptionMessage(
            sprintf(
                'The "Reflection\Tests\Validator\FunctionValidatorTest::reflectionMethod()" ' .
                'method must have %s arguments, 2 given.',
                $count
            )
        );
    }

    /**
     * @dataProvider providerWrongReturnType
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testWrongReturnType(string $method, string $expectedExceptionMessage)
    {
        $this->expectExceptionMessage($expectedExceptionMessage);

        (new FunctionValidator(new \ReflectionMethod($this, $method)))
            ->setReturnByReference(true)
            ->setReturnType(\Iterator::class)
            ->addArgument($this->firstArgument)
            ->addArgument($this->secondArgument);
    }

    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testWrongReturnReference()
    {
        $this->expectExceptionMessage(
            'The "Reflection\Tests\Validator\FunctionValidatorTest::reflectionMethod()" ' .
            'method must not return a value by reference.'
        );

        (new FunctionValidator(new \ReflectionMethod($this, 'reflectionMethod')))
            ->setReturnByReference(false)
            ->setReturnType('int')
            ->addArgument($this->firstArgument)
            ->addArgument($this->secondArgument);
    }

    public function providerWrongReturnType()
    {
        $this->setUpArguments();

        return [
            [
                'reflectionMethod',
                'The "Reflection\Tests\Validator\FunctionValidatorTest::reflectionMethod()" ' .
                'method must return a value of "Iterator", but at the moment it is "int".',
            ],
            [
                'reflectionMethodWithoutReturnType',
                'The type of returning value for "Reflection\Tests\Validator\FunctionValidator' .
                'Test::reflectionMethodWithoutReturnType()" method is not specified.',
            ],
        ];
    }

    public function providerWrongNumberOfArguments()
    {
        $this->setUpArguments();

        return [
            [[]],
            [[$this->firstArgument, $this->secondArgument, $this->secondArgument]],
        ];
    }
}
