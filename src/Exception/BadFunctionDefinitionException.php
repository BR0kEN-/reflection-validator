<?php

namespace Reflection\Validator\Exception;

/**
 * An exception for marking a definition of function/method as unacceptable.
 */
class BadFunctionDefinitionException extends \ReflectionException implements \ArrayAccess
{
    /**
     * Global tokens to replace in error messages.
     *
     * @var string[]
     */
    private $tokens = [];
    /**
     * A list of error messages.
     *
     * @var string[]
     */
    private $errors = [];

    /**
     * BadFunctionDefinitionException constructor.
     *
     * @param \ReflectionFunctionAbstract $function
     *   A function/method to collect violations for.
     *
     * @throws self
     *   When argument is not of "ReflectionMethod" or "ReflectionFunction" type.
     */
    public function __construct(\ReflectionFunctionAbstract $function)
    {
        parent::__construct();

        if ($function instanceof \ReflectionMethod) {
            $this->tokens['@function'] = sprintf('"%s::%s()" method', $function->class, $function->name);
        } elseif ($function instanceof \ReflectionFunction) {
            $this->tokens['@function'] = sprintf('"%s()" function', $function->name);
        } else {
            $this->message = sprintf(
                'The argument of the "%s()" must be of "%s" or "%s" type, "%s" given.',
                __METHOD__,
                \ReflectionMethod::class,
                \ReflectionFunction::class,
                get_class($function)
            );

            throw $this;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($token)
    {
        return isset($this->tokens[$token]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($token)
    {
        return $this->tokens[$token];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($token, $value)
    {
        $this->tokens[$token] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($token)
    {
        unset($this->tokens[$token]);
    }

    /**
     * Adds an error message to the list.
     *
     * @param string $message
     *   A message to add.
     * @param string[] $tokens
     *   An array of additional tokens to replace in a message.
     */
    public function addError(string $message, array $tokens = [])
    {
        $this->errors[] = strtr($message, $this->tokens + $tokens);
    }

    /**
     * Returns a list of error messages.
     *
     * @return string[]
     *   A list of error messages.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Throws an instance of self in a case when errors list is not empty.
     *
     * @throws self
     */
    public function throwIfErrorsExist()
    {
        if (!empty($this->errors)) {
            $this->tokens = [];
            $this->message = implode(PHP_EOL, $this->errors);

            throw $this;
        }
    }
}
