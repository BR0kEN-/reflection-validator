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

## Examples

Besides of [fully-operable examples](examples), you can check a quick use case:

```php
(new MethodValidator(object<ReflectionMethod>, string<FQCN>))
    ->addArgument(
        (new ArgumentSpecification('form'))
            ->setType('array')
            ->setOptional(false)
            ->setPassedByReference(true)
    )
    ->addArgument(
        (new ArgumentSpecification('formState'))
            ->setType(Iterator::class)
            ->setOptional(false)
            ->setPassedByReference(false)
    );
```
