# Rest Converter Annotations

Com as *annotations* @Body e @Query você converte automaticamente os *payloads* ou *queries strings*,
respectivamente, em objetos nos métodos dos controladores.

## Sumário

- [Como funciona?](#como-funciona)
- Uso
  - [Anotação @Body](#anotação-body)
  - [Anotação @Query](#anotação-query)
  - [Formato do DTO (Data Transfer Object)](#formato-do-dto-data-transfer-object)
- [Prioridade do RestListener](#prioridade-do-restlistener)

## Como funciona?

O *listener* SilasYudi\RestBootBundle\EventListener\RestListener verifica a presença das anotações
@Body ou @Query nos métodos dos controladores nas rotas acionadas. 

Se possuir, é realizada a conversão do conteúdo para objeto através do [SerializerInterface](https://jmsyst.com/libs/serializer)
do JMS. Se essa anotação for @Body, é utilizado o conteúdo do *body* da requisição para a conversão. Se a anotação
for @Query, é utilizado a *query string* da URL da requisição para conversão.

## Uso

### Anotação @Body

#### Requisitos 

Para converter um *payload* de uma requisição POST, PUT, PATCH ou DELETE, este deve ser enviada no *body* da requisição
em formato JSON.

#### Uso da anotação no controlador

A anotação @Body deve ser utilizada na assinatura do método em que se inicia uma rota, como no exemplo abaixo: 

```php
use Symfony\Component\Routing\Annotation\Route;
use SilasYudi\RestBootBundle\Rest\Annotation\Body;

/**
 * @Route("/action", methods={"POST"})
 * @Body(parameter="myObject") 
 */
public function __invoke(MyObjectDTO $myObject) 
{ 
    ...
```

**Importante:**
- Você DEVE inserir como argumento na anotação @Body o nome da variável do objeto que receberá o *payload*;
- A assinatura do método DEVE possuir o objeto para o qual o *payload* será convertido e este objeto DEVE ser tipado;
- Cada método só suporta UMA *annotation* Rest. Se você utilizar mais de uma, a aplicação considerará válida apenas a primeira.

Observação: ao passar o argumento na anotação @Body, ambas as formas abaixo são válidas:

```php
/**
 * @Body(parameter="myObject") 
 */

/**
 * @Body("myObject") 
 */
```

### Anotação @Query

A anotação @Query deve ser utilizada na assinatura do método em que se inicia uma rota, como no exemplo abaixo:

```php
use Symfony\Component\Routing\Annotation\Route;
use SilasYudi\RestBootBundle\Rest\Annotation\Query;

/**
 * @Route("/action", methods={"GET"})
 * @Query(parameter="myObject") 
 */
public function __invoke(MyObjectDTO $myObject) 
{ 
    ...
```

**Importante:**
- Você DEVE inserir como argumento na anotação @Query o nome da variável do objeto que receberá a *query string*;
- A assinatura do método DEVE possuir o objeto para o qual a *query string* será convertida e este objeto DEVE ser tipado;
- Cada método só suporta UMA *annotation* Rest. Se você utilizar mais de uma, a aplicação considerará válida apenas a primeira.

Observação: ao passar o argumento na anotação @Query, ambas as formas abaixo são válidas:

```php
/**
 * @Query(parameter="myObject") 
 */

/**
 * @Query("myObject") 
 */
```

### Formato do DTO (Data Transfer Object)

Se você não fez nenhuma configuração específica do SerializerInterface no seu projeto, o Kernel irá carregar o SerializerInterface padrão. 
Isso quer dizer que seu objeto DEVE conter as propriedades com os mesmos nomes do *payload* e DEVE conter os métodos *getters* e *setters* correspondentes.

Se você fez configurações específicas para o SerializerInterface, o seu objeto deve estar em um formato que permita ser desserializado através dessas configurações.

**Algumas informações importantes para a configuração *default* do SerializerInterface:**
- As propriedades tipadas sofrerão o *cast* padrão do PHP.
- Tipos *int* DEVEM ser representados como inteiro ou string numérica.
- Tipos *float* DEVEM ser representados como decimal ou string decimal. Tipos *float* DEVEM ter as casas decimais separadas por ponto.
- Tipos *boolean* DEVEM:
  - ter o valor *true* representado como: booleano *true*, inteiro ou decimal diferente de 0, ou string diferente de string vazia ou string "0".
  - ter o valor *false* representado como: booleano *false*, inteiro ou decimal igual a 0, ou string igual string vazia ou string "0".
- Datas DEVEM possuir uma anotação `@JMS\Serializer\Annotation\Type` para especificar o formato (consulte a [documentação](https://jmsyst.com/libs/serializer/master/reference/annotations#type)).
- Arrays DEVEM possuir uma anotação `@JMS\Serializer\Annotation\Type` para especificar o tipo (consulte a [documentação](https://jmsyst.com/libs/serializer/master/reference/annotations#type)).

Para mais informações, consulte a documentação do [Serializer](https://jmsyst.com/libs/serializer).

## Prioridade do RestListener

A prioridade do RestListener na cadeia de chamadas está configurada como **1** para o evento *kernel.controller*.
Para alterar este valor, nas configurações do Symfony especifique da seguinte forma:

```yaml
parameters:
  restboot.rest.priority.controller: 0 #alterando para 0
```

Obs.: o Symfony permite que se especifique quaisquer valores inteiros positivos, negativos ou zero.
