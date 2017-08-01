<?php

namespace Reflection\Validator;

use Reflection\Validator\Exception\BadFunctionDefinitionException;

/**
 * Specification of the method/function argument.
 */
class ArgumentSpecification
{
    use TypeValidator;

    /**
     * A name of the argument.
     *
     * @var string
     */
    protected $name = '';
    /**
     * A type of the argument.
     *
     * @var string
     */
    protected $type = '';
    /**
     * Indicates whether an argument must be optional.
     *
     * @var bool
     */
    protected $optional = false;
    /**
     * Indicates whether an argument must be passed by reference.
     *
     * @var bool
     */
    protected $passedByReference = false;
    /**
     * A parameter to compare specification with.
     *
     * @var \ReflectionParameter
     */
    private $parameter;
    /**
     * A bag for errors.
     *
     * @var BadFunctionDefinitionException
     */
    private $exception;

    /**
     * ArgumentSpecification constructor.
     *
     * @param string $name
     *   A name of the argument.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Defines a type of the argument.
     *
     * @param string $type
     *   A type of the argument.
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = trim($type);

        return $this;
    }

    /**
     * Defines whether an argument is optional.
     *
     * @param bool $state
     *   An indicator of a state.
     *
     * @return $this
     */
    public function setOptional(bool $state): self
    {
        $this->optional = $state;

        return $this;
    }

    /**
     * Defines whether an argument is passed by reference.
     *
     * @param bool $state
     *   An indicator of a state.
     *
     * @return $this
     */
    public function setPassedByReference(bool $state): self
    {
        $this->passedByReference = $state;

        return $this;
    }

    /**
     * Validates whether argument specification according to parameter definition.
     *
     * @param \ReflectionParameter $parameter
     *   A parameter to compare specification with.
     * @param BadFunctionDefinitionException|null $exception
     *   An exception to collect violations.
     */
    public function validate(\ReflectionParameter $parameter, BadFunctionDefinitionException $exception = null)
    {
        $stop_here = false;

        if (null === $exception) {
            $exception = new BadFunctionDefinitionException($parameter->getDeclaringFunction());
            $stop_here = true;
        }

        $exception['@argument'] = sprintf('argument %d', $parameter->getPosition() + 1);

        $this->parameter = $parameter;
        $this->exception = $exception;

        $this->checkName();
        $this->checkOptional();
        $this->checkReference();
        $this->checkType();

        // An exception was not passed from the outside which means that we
        // don't have an execution context and can fail here.
        if ($stop_here) {
            $exception->throwIfErrorsExist();
        }
    }

    /**
     * Checks that parameter is named correctly.
     */
    private function checkName()
    {
        if (empty($this->exception->getErrors())) {
            $given_name = $this->parameter->getName();

            if ($this->name !== $given_name) {
                $this->exception->addError(
                    'The @argument of the @function has the "@givenName" name, but must be "@requiredName".',
                    [
                        '@givenName' => $given_name,
                        '@requiredName' => $this->name,
                    ]
                );
            }
        }
    }

    /**
     * Checks optionality of the parameter.
     */
    private function checkOptional()
    {
        if (empty($this->exception->getErrors())) {
            $has_default_value = $this->parameter->isDefaultValueAvailable();

            if ($this->optional) {
                if (!$has_default_value) {
                    $this->exception->addError('The @argument of the @function must be optional.');
                }
            } else {
                if ($has_default_value) {
                    $this->exception->addError('The @argument of the @function must not be optional.');
                }
            }
        }
    }

    /**
     * Checks referenceability of the parameter.
     */
    private function checkReference()
    {
        if (empty($this->exception->getErrors())) {
            $has_passed_by_reference = $this->parameter->isPassedByReference();

            if ($this->passedByReference) {
                if (!$has_passed_by_reference) {
                    $this->exception->addError('The @argument of the @function must be passed by reference.');
                }
            } else {
                if ($has_passed_by_reference) {
                    $this->exception->addError('The @argument of the @function must not be passed by reference.');
                }
            }
        }
    }

    /**
     * Checks a type of the parameter.
     */
    private function checkType()
    {
        if (empty($this->exception->getErrors())) {
            $parameter_type = $this->parameter->getType();

            if ('' === $this->type) {
                $this->exception->addError('The type of the @argument of the @function is not specified.');
            } elseif (null === $parameter_type) {
                $this->exception->addError(
                    'The type of the @argument of the @function must be of "@argumentType" type, none given.',
                    [
                        '@argumentType' => $this->type,
                    ]
                );
            } elseif (!$this->isTypeValid($parameter_type, $this->type)) {
                $this->exception->addError(
                    'The @argument of the @function must be of "@argumentType" type, "@parameterType" given.',
                    [
                        '@argumentType' => $this->type,
                        '@parameterType' => $parameter_type,
                    ]
                );
            }
        }
    }
}
