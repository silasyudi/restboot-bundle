# SymfonyBoot

[![Tests](https://github.com/symfonyboot/symfonyboot-bundle/actions/workflows/tests.yml/badge.svg)](https://github.com/symfonyboot/symfonyboot-bundle/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/symfonyboot/symfonyboot-bundle/branch/main/graph/badge.svg)](https://codecov.io/gh/symfonyboot/symfonyboot-bundle)

SymfonyBoot is a Symfony package to speed up the development of rest apis.

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
composer require symfonyboot/symfonyboot-bundle
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

#### Example without SymfonyBoot:

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

#### Example with SymfonyBoot:

```php
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;

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

#### Example without SymfonyBoot:

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

#### Example with SymfonyBoot:

```php
use SymfonyBoot\SymfonyBootBundle\Transaction\Annotation\Transaction;

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
