# Reflection Validator

[![Build Status](https://img.shields.io/travis/BR0kEN-/reflection-validator/master.svg?style=flat-square)](https://travis-ci.org/BR0kEN-/reflection-validator)
[![Code coverage](https://img.shields.io/scrutinizer/coverage/g/BR0kEN-/reflection-validator/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/BR0kEN-/reflection-validator/?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/BR0kEN-/reflection-validator.svg?style=flat-square)](https://scrutinizer-ci.com/g/BR0kEN-/reflection-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/reflection/validator.svg?style=flat-square)](https://packagist.org/packages/reflection/validator)
[![Latest Stable Version](https://poser.pugx.org/reflection/validator/v/stable?format=flat-square)](https://packagist.org/packages/reflection/validator)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://packagist.org/packages/reflection/validator)

## When might it be useful?

Sometimes you may want to have an unlimited sequence of methods, which cannot be restricted/controlled by the interface. In this case on a "compilation" stage, you can restrict those methods to follow the standards you expect.

A real example from Drupal/Symfony world: http://cgit.drupalcode.org/form_alter_service/tree/src/FormAlterCompilerPass.php

## Example

```php
namespace Path\To\Annotations;

use Reflection\Validator\MethodValidator;
use Reflection\Validator\ArgumentSpecification;
use Reflection\Validator\Annotation\ReflectionValidatorMethodAnnotationInterface;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class ExampleAnnotation implements ReflectionValidatorMethodAnnotationInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate(\ReflectionMethod $method)
    {
        // - The method must be a member of "Path\To\Components\ExampleClass"
        //   or its children.
        // - The method must have 2 arguments (not less and not more, exactly
        //   2).
        // - The first argument of the method must be of "array" type, passed
        //   by reference and with the "form" name.
        // - The second argument of the method must be of "FormStateInterface"
        //   type, not passed by reference and with the "formState" name.
        (new MethodValidator($method, 'Path\To\Components\ExampleClass'))
            ->addArgument(
                (new ArgumentSpecification('form'))
                    ->setType('array')
                    ->setOptional(false)
                    ->setPassedByReference(true)
            )
            ->addArgument(
                (new ArgumentSpecification('formState'))
                    ->setType(FormStateInterface::class)
                    ->setOptional(false)
                    ->setPassedByReference(false)
            );
    }
}
```

```php
namespace Path\To\Components;

class ExampleClass
{
    /**
     * @ExampleAnnotation
     */
    public function exampleMethod(array &$form, FormStateInterface $formState)
    {
      // An instance of the "\ReflectionMethod" for this method will be passed
      // to the "validate()" method of the "ExampleAnnotation" annotation.
    }
}
```

```php
use Path\To\Components\ExampleClass;
use Path\To\Annotations\ExampleAnnotation;
use Reflection\Validator\Annotation\ReflectionValidatorAnnotationReader;

$reader = new ReflectionValidatorAnnotationReader();
$reader->addNamespace('Path\To\Annotations');

$method = new \ReflectionMethod(ExampleClass::class, 'exampleMethod');
$annotation = $reader->getMethodAnnotation($method, ExampleAnnotation::class);
```
