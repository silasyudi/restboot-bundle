# SymfonyBoot

[![Tests](https://github.com/symfonyboot/symfonyboot-bundle/actions/workflows/tests.yml/badge.svg)](https://github.com/symfonyboot/symfonyboot-bundle/actions/workflows/tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/00b538dd4da9d4e85f6d/maintainability)](https://codeclimate.com/github/symfonyboot/symfonyboot-bundle/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/00b538dd4da9d4e85f6d/test_coverage)](https://codeclimate.com/github/symfonyboot/symfonyboot-bundle/test_coverage)

SymfonyBoot é um *bundle* Symfony para acelerar o desenvolvimento de API's REST.

## Sumário
- [Idioma / Language](#idioma--language)
- [Instalação](#instalao)
- [Pré-requisitos](#pr-requisitos)
- [Funcionalidades](#funcionalidades)
- [Documentação](#documentao)

## Idioma / Language

Read the English :us: version [here](README.md).

## Instalação

```sh
composer require symfonyboot/symfonyboot-bundle
```

## Pré-requisitos

- PHP 7.4+
- Composer
- Symfony 4.4+ / 5+
- Doctrine 2+

## Funcionalidades

### Conversão de *payloads* em objetos diretamente nos métodos dos controladores 

Com as *annotations* @Body e @Query você converte automaticamente os *payloads* ou *queries*,
respectivamente, em objetos nos métodos dos controladores.

#### Exemplo sem SymfonyBoot:

```php
/**
 * Conversão de Payload com algum serializador 
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

#### Exemplo com SymfonyBoot:

```php
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;

/**
 * Conversão de Payload com annotation @Body
 * @Route("/myAction", methods={"POST"})
 * @Body("myObject")
 */
public function __invoke(MyObjectDTO $myObject)
{
    ...
```

### Gerencie facilmente a transação do Doctrine

Com a *annotation* @Transaction, a verbosidade do gerenciamento de transação do Doctrine é reduzida.

#### Exemplo sem SymfonyBoot:

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

#### Exemplo com SymfonyBoot:

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

## Documentação

**Português** :brazil:
- [REST Converter Annotations](Documentation/REST_PT_BR.md)
- [Transaction Annotation](Documentation/TRANSACTION_PT_BR.md)

**Inglês** :us:
- [REST Converter Annotations](Documentation/REST.md)
- [Transaction Annotation](Documentation/TRANSACTION.md)

