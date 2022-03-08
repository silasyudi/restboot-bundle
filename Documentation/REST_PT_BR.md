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

O *listener* SymfonyBoot\SymfonyBootBundle\EventListener\RestListener verifica a presença das anotações
@Body ou @Query nos métodos dos controladores nas rotas acionadas. 

Se possuir, é realizada a conversão do conteúdo para objeto através do [SerializerInterface](https://symfony.com/doc/current/components/serializer.html)
do Symfony. Se essa anotação for @Body, é utilizado o conteúdo do *body* da requisição para a conversão. Se a anotação
for @Query, é utilizado a *query string* da URL da requisição para conversão.

## Uso

### Anotação @Body

#### Requisitos 

Para converter um *payload* de uma requisição POST, PUT, PATCH ou DELETE, este deve ser enviada no *body* da requisição, 
em [formato suportado](https://symfony.com/doc/current/serializer.html#adding-normalizers-and-encoders) pelo
SerializerInterface do Symfony (como por exemplo, JSON ou XML).

#### Content-type

É importante que no cabeçalho da requisição seja enviado o *content-type* apropriado. Na falta desta informação, 
a aplicação utilizará o formato JSON como padrão. 

Você pode alterar o content-type padrão nas configurações do Symfony, da seguinte forma:

```yaml
parameters:
  symfonyboot.rest.payload.format: 'xml' #alterando para XML
```

Observe que aqui não se usa o media-type, mas sim o nome do formato.

#### Uso da anotação no controlador

A anotação @Body deve ser utilizada na assinatura do método em que se inicia uma rota, como no exemplo abaixo: 

```php
use Symfony\Component\Routing\Annotation\Route;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;

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
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;

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
- Datas DEVEM ser representadas no formato RFC3339 (Y-m-d\TH:i:sP)

Para mais informações, consulte a documentação do [Serializer Component](https://symfony.com/doc/current/components/serializer.html).

## Prioridade do RestListener

A prioridade do RestListener na cadeia de chamadas está configurada como **1** para o evento *kernel.controller*.
Para alterar este valor, nas configurações do Symfony especifique da seguinte forma:

```yaml
parameters:
  symfonyboot.rest.priority.controller: 0 #alterando para 0
```

Obs.: o Symfony permite que se especifique quaisquer valores inteiros positivos, negativos ou zero.
