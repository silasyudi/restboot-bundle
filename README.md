# RestBoot

[![Tests](https://github.com/silasyudi/restboot-bundle/actions/workflows/tests.yml/badge.svg)](https://github.com/silasyudi/restboot-bundle/actions/workflows/tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/6e06d94e4468b1e8f655/maintainability)](https://codeclimate.com/github/silasyudi/restboot-bundle/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/6e06d94e4468b1e8f655/test_coverage)](https://codeclimate.com/github/silasyudi/restboot-bundle/test_coverage)

Symfony package to speed up the development of rest apis.

## Summary
- [Language / Idioma](#language--idioma)
- [Instalation](#instalation)
- [Requirements](#requirements)
- [Features](#features)
- [Documentation](#documentation)

## Language / Idioma

Leia a versão em português :brazil: [aqui](README_PT_BR.md).

## Instalation

```sh
composer require silasyudi/restboot-bundle
```

## Requirements

- PHP 7.4+
- Composer
- Symfony 4.4+ / 5+
- Doctrine 2+

## Features

### Convert payloads into objects in the controller methods

With @Body and @Query annotations, you can automatically convert your payloads
and queries into objects in the controller methods.

#### Example without RestBoot:

```php
/**
 * Payload converted with some serializer
 * @Route("/myAction", methods={"POST"}) 
 */
public function __invoke(Request $request, SerializerInterface $serializer)
{
    $myObject = $serializer->deserialize(
        $request->getContent(),
        MyObject::class,
        'json'
    );
    ...
```

#### Example with RestBoot:

```php
use SilasYudi\RestBootBundle\Rest\Annotation\Body;

/**
 * Payload converted with @Body annotation
 * @Route("/myAction", methods={"POST"})
 * @Body("myObject")
 */
public function __invoke(MyObjectDTO $myObject)
{
    ...
```

### Easily manage the Doctrine transaction

With @Transaction annotation, you can reduce the verbosity of Doctrine transaction management.

#### Example without RestBoot:

```php
/**
 * @Route("/myAction", methods={"POST"}) 
 */
public function __invoke()
{
    $connection = $this->getDoctrine()->getConnection(); 

    try {
        $connection->beginTransaction();
            
        $this->service->someAction();

        if ($connection->isTransactionActive()) {
            $connection->commit();
        }
    } catch (SomeException $exception) {
        if ($connection->isTransactionActive()) {
            $connection->rollback();
        }
    }

    ...
```

#### Example with RestBoot:

```php
use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;

...

/**
 * @Route("/myAction", methods={"POST"})
 * @Transaction("myConnection")
 */
public function __invoke()
{
    $this->service->someAction();
    ...
```

## Documentation

**English** :us:
- [REST Converter Annotations](Documentation/REST.md)
- [Transaction Annotation](Documentation/TRANSACTION.md)

**Portuguese** :brazil:
- [REST Converter Annotations](Documentation/REST_PT_BR.md)
- [Transaction Annotation](Documentation/TRANSACTION_PT_BR.md)
