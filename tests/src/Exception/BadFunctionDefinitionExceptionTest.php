<?php

namespace Reflection\Tests\Validator\Exception;

use PHPUnit\Framework\TestCase;
use Reflection\Tests\Validator\Data;
use Reflection\Validator\Exception\BadFunctionDefinitionException;

class BadFunctionDefinitionExceptionTest extends TestCase
{
    use Data;

    /**
     * @dataProvider providerConstructor
     */
    public function testConstructor(\ReflectionFunctionAbstract $function, string $expectedToken)
    {
        $exception = new BadFunctionDefinitionException($function);

        $this->assertAttributeContains($expectedToken, 'tokens', $exception);

        // Test the "offsetSet()".
        $exception['@token'] = 1;

        // Test the "offsetExists()",
        $this->assertTrue(isset($exception['@token']));
        // Test the "offsetGet()",
        $this->assertSame(1, $exception['@token']);

        // No exception must be thrown when no errors exist.
        $exception->throwIfErrorsExist();
        $exception->addError('The @function must have @token and @customToken.', [
            '@customToken' => 2,
        ]);

        unset($exception['@token']);

        // The "@token" has been removed by "unset()".
        $this->assertFalse(isset($exception['@token']));
        // The "@customToken" has not been added.
        $this->assertFalse(isset($exception['@customToken']));
        $this->assertAttributeContains(sprintf('The %s must have 1 and 2.', $expectedToken), 'errors', $exception);
        $this->assertNotEmpty($exception->getErrors());

        try {
            $exception->throwIfErrorsExist();
            $this->fail('Expected exception has not been thrown.');
        } catch (BadFunctionDefinitionException $e) {
        }
    }

    public function providerConstructor()
    {
        return [
            [
                new \ReflectionFunction(function () {}),
                '"Reflection\Tests\Validator\Exception\{closure}()" function',
            ],
            [
                new \ReflectionMethod($this, 'emptyMethod'),
                '"Reflection\Tests\Validator\Exception\BadFunctionDefinitionExceptionTest::emptyMethod()" method',
            ],
        ];
    }

    /**
     * @expectedException \Reflection\Validator\Exception\BadFunctionDefinitionException
     */
    public function testConstructorException()
    {
        new BadFunctionDefinitionException(new class extends \ReflectionFunctionAbstract {
            public static function export()
            {
            }

            public function __toString()
            {
            }
        });
    }
}
