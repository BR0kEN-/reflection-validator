<?php

namespace Reflection\Examples\Validator\Simple\Component;

class SimpleComponent
{
    /**
     * @SimpleAnnotation()
     *
     * @see \Reflection\Examples\Validator\Simple\Annotation\SimpleAnnotation::validate()
     */
    public function method(array &$form, \Iterator $formState)
    {
        // Play with argument types, names, reference to see how the validation works.
    }
}
