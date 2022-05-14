# Transaction Annotations

Com a *annotation* @Transaction, a verbosidade do gerenciamento de transação do Doctrine é reduzida.

## Sumário

- [Como funciona?](#como-funciona)
- Uso
  - [Classe](#classe)
  - [Método](#método)
  - [Argumentos](#argumentos)
- [Prioridades do TransactionListener](#prioridades-do-transactionlistener)

## Como funciona?

O *listener* SilasYudi\RestBootBundle\EventListener\TransactionListener verifica a presença da anotação @Transaction
nos métodos dos controladores nas rotas acionadas.

Se possuir, é criado um *manager* TransactionManager, iniciando a transação no EntityManager passado em parâmetro da anotação (ou no 
EntityManager *default*, caso nenhum seja especificado). E ao encerrar a requisição, automaticamente o TransactionListener irá realizar 
o *commit*, caso a requisição não entre em estado de exceção, ou o *rollback*, caso entre em estado de exceção.

## Uso

### Classe

A anotação @Transaction pode ser utilizada em escopo de classe ou de métodos. No caso de escopo de classe, deve ser utilizado sobre a declaração de classe:

```php
use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;

/**
 * @Transaction(connection="manager") 
 */
public class Controller 
{ 
    ...
```

**Importante:**
- Todas as rotas dessa classe que NÃO possuírem uma anotação @Transaction específica no método por onde a requisição inicia utilizarão a anotação @Transaction da classe. 
- Cada declaração de classe só suporta UMA anotação @Transaction. Se você utilizar mais de uma, a aplicação considerará válida apenas a primeira.
- Cada anotação @Transaction só suporta UM EntityManager.

### Método

No caso de escopo de método, deve ser declarada sobre a assinatura do método por onde a requisição inicia:

```php
use Symfony\Component\Routing\Annotation\Route;
use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;

/**
 * @Route("/action", methods={"POST"})
 * @Transaction(connection="manager") 
 */
public function __invoke() 
{ 
    ...
```

**Importante:**
- Se a sua classe já possui uma anotação @Transaction, a anotação no método por onde a requisição inicia sobrescreverá a anotação @Transaction da classe.
- Cada método só suporta UMA anotação @Transaction. Se você utilizar mais de uma, a aplicação considerará válida apenas a primeira.
- Cada anotação @Transaction só suporta UM EntityManager.

### Argumentos

Ao passar o argumento na anotação @Transaction, ambas as formas abaixo são válidas, tanto para declaração em classe quanto em método:

```php
/**
 * @Transaction(connection="manager") 
 */

/**
 * @Transaction("manager") 
 */
```

O uso de anotação @Transaction sem argumentos, como no exemplo seguinte, é permitido e corresponde a utilizar o EntityManager *default*.

```php
/**
  * @Transaction
  */
```

## Prioridades do TransactionListener

A prioridade do TransactionListener na cadeia de chamadas está configurada como **1** para os três eventos utilizados: *kernel.controller*,
*kernel.response* e *kernel.exception*. Para alterar estes valores, nas configurações do Symfony especifique da seguinte forma:

```yaml
parameters:
  restboot.transaction.priority.controller: 2 #alterando para 2
  restboot.transaction.priority.response: 3 #alterando para 3
  restboot.transaction.priority.exception: 4 #alterando para 4
```

**Importante:**
- O Symfony permite que se especifique quaisquer valores inteiros positivos, negativos ou zero.
- Você pode alterar a prioridade de quaisquer eventos do TransactionListener de forma individualizada e independente.
