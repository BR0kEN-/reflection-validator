<?php

namespace Reflection\Validator;

use Reflection\Validator\Exception\BadFunctionDefinitionException;

/**
 * Validates function specification.
 */
class FunctionValidator
{
    use TypeValidator;

    /**
     * A function/method to check the specification of.
     *
     * @var \ReflectionFunctionAbstract
     */
    protected $target;
    /**
     * Violations collector.
     *
     * @var BadFunctionDefinitionException
     */
    protected $exception;
    /**
     * A list of arguments that function/method must have.
     *
     * @var ArgumentSpecification[]
     */
    protected $arguments = [];
    /**
     * A type of returning value by the function.
     *
     * @var string
     */
    protected $returnType = '';
    /**
     * A state whether function must return value by reference.
     *
     * @var null|bool
     */
    protected $returnByReference = null;

    /**
     * FunctionValidator constructor.
     *
     * @param \ReflectionFunctionAbstract $function
     *   A function/method to check the specification of.
     */
    public function __construct(\ReflectionFunctionAbstract $function)
    {
        $this->target = $function;
        $this->exception = new BadFunctionDefinitionException($function);
    }

    /**
     * Defines an argument that function must have.
     *
     * @param ArgumentSpecification $argument
     *   A specification of the argument.
     *
     * @return $this
     */
    public function addArgument(ArgumentSpecification $argument): self
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Require the function to return a value by reference.
     *
     * @param bool $state
     *   An indicator of a state.
     *
     * @return $this
     */
    public function setReturnByReference(bool $state): self
    {
        $this->returnByReference = $state;

        return $this;
    }

    /**
     * Require the function to specify a type of returning value.
     *
     * @param string $type
     *   A type of returning value.
     *
     * @return $this
     */
    public function setReturnType(string $type): self
    {
        $this->returnType = trim($type);

        return $this;
    }

    /**
     * Runs a validation during destruction.
     */
    public function __destruct()
    {
        $this->checkArguments();
        $this->checkReturnType();
        $this->checkReturnByReference();

        $this->exception->throwIfErrorsExist();
    }

    /**
     * Validates arguments of a function.
     */
    protected function checkArguments()
    {
        if (empty($this->exception->getErrors())) {
            $arguments_count = count($this->arguments);
            $parameters_count = $this->target->getNumberOfParameters();

            if ($parameters_count !== $arguments_count) {
                $this->exception->addError(
                    'The @function must have @argumentsCount arguments, @parametersCount given.',
                    [
                        '@argumentsCount' => $arguments_count,
                        '@parametersCount' => $parameters_count,
                    ]
                );
            } else {
                foreach ($this->target->getParameters() as $i => $parameter) {
                    $this->arguments[$i]->validate($parameter, $this->exception);
                }
            }
        }
    }

    /**
     * Validates returning type.
     */
    protected function checkReturnType()
    {
        if ('' !== $this->returnType && empty($this->exception->getErrors())) {
            $return_type = $this->target->getReturnType();

            if (null === $return_type) {
                $this->exception->addError('The type of returning value for @function is not specified.');
            } elseif (!$this->isTypeValid($return_type, $this->returnType)) {
                $this->exception->addError(
                    'The @function must return a value of "@returnType", but at the moment it is "@currentType".',
                    [
                        '@returnType' => $this->returnType,
                        '@currentType' => $return_type,
                    ]
                );
            }
        }
    }

    /**
     * Validates returning by reference.
     */
    protected function checkReturnByReference()
    {
        if (null !== $this->returnByReference && empty($this->exception->getErrors())) {
            $returns_by_reference = $this->target->returnsReference();

            if ($this->returnByReference) {
                if (!$returns_by_reference) {
                    $this->exception->addError('The @function must return a value by reference.');
                }
            } else {
                if ($returns_by_reference) {
                    $this->exception->addError('The @function must not return a value by reference.');
                }
            }
        }
    }
}
