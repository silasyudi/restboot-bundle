# Rest Converter Annotations

Com as *annotations* @Body e @Query você converte automaticamente os *payloads* ou *queries*,
respectivamente, em objetos nos métodos dos controladores.

## Sumário

- [Como funciona?](#como-funciona)
- Uso
  - [Anotação @Body](#anotao-body)
  - [Anotação @Query](#anotao-query)

## Como funciona?

O *subscriber* SymfonyBoot\SymfonyBootBundle\EventSubscriber\RestSubscriber verifica a presença das anotações
@Body ou @Query nos métodos dos controladores nas rotas acionadas. Se possuir, é realizada a conversão do *payload* 
para objeto através do [SerializerInterface](https://symfony.com/doc/current/components/serializer.html) do Symfony.

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
  symfonyboot.defaults.rest.format: 'xml' #alterando para XML
```

Observe que aqui não se usa o media-type, mas sim o nome do formato.

#### Uso da anotação no controlador

A anotação @Body deve ser utilizada na assinatura do método em conjunto com a anotação @Route, como no exemplo abaixo: 

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

Importante:
- Você DEVE inserir como argumento na anotação @Body o nome da variável do objeto que receberá o payload;
- A assinatura do método DEVE possuir o objeto para o qual o payload será convertido e este objeto DEVE ser tipado;
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

### Formato do DTO (Data Transfer Object)

Se você não fez nenhuma configuração específica do SerializerInterface no seu projeto, o Kernel irá carregar o SerializerInterface padrão.



Se você fez configurações específicas para o SerializerInterface, o seu objeto deve estar em um formato que permita ser desserializado através dessas configurações.

### Anotação @Query

A 
