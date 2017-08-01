<?php

namespace Reflection\Validator;

/**
 * Validates method specification.
 */
class MethodValidator extends FunctionValidator
{
    /**
     * MethodValidator constructor.
     *
     * @param \ReflectionMethod $method
     *   A method to validate.
     * @param string $required_class
     *   A class in a scope of which method allowed to be defined.
     */
    public function __construct(\ReflectionMethod $method, string $required_class = '')
    {
        parent::__construct($method);

        if ('' !== $required_class && !is_a($method->getDeclaringClass()->getName(), $required_class, true)) {
            $this->exception->addError('The @function defined in the class that not inherits "@requiredClass".', [
                '@requiredClass' => $required_class,
            ]);
        }
    }
}
