# Transaction Annotations

With @Transaction annotation, you can reduce the verbosity of Doctrine transaction management.

## Summary

- [How it works?](#how-it-works)
- Usage
    - [Class](#class)
    - [Method](#method)
    - [Arguments](#arguments)
- [TransactionListener priority](#transactionlistener-priority)

## How it works?

The SymfonyBoot\SymfonyBootBundle\EventListener\TransactionListener listener checks for the presence of annotation
@Transaction on controller methods on triggered routes.

If so, a TransactionManager manager is created, starting the transaction in the EntityManager passed in the annotation
parameter (or in the EntityManager default, if none is specified). And when closing the request, the TransactionListener
will automatically perform the commit, if the request does not enter an exception state, or the rollback, if it enters
an exception state.

## Usage

### Class

The @Transaction annotation can be used at either class or method scope.
In the case of class scope, it should be used over the class declaration:

```php
use SymfonyBoot\SymfonyBootBundle\Transaction\Annotation\Transaction;

/**
 * @Transaction(connection="manager") 
 */
public class Controller 
{ 
    ...
```

**Important:**
- All routes of this class that DO NOT have a specific @Transaction annotation in the method where the request starts will use the class's @Transaction annotation.
- Each class declaration supports only ONE @Transaction annotation. If you use more than one, the application will consider only the first one valid.
- Each @Transaction annotation supports only ONE EntityManager.

### Method

In the case of method scope, it must be declared over the method signature where the request starts:

```php
use Symfony\Component\Routing\Annotation\Route;
use SymfonyBoot\SymfonyBootBundle\Transaction\Annotation\Transaction;

/**
 * @Route("/action", methods={"POST"})
 * @Transaction(connection="manager") 
 */
public function __invoke() 
{ 
    ...
```

**Important:**
- If your class already has a @Transaction annotation, the annotation on the method where the request starts will overwrite the class's @Transaction annotation.
- Each method supports only ONE @Transaction annotation. If you use more than one, the application will only consider the first one valid.
- Each @Transaction annotation supports only ONE EntityManager.

### Arguments

When passing the argument in the @Transaction annotation, both forms below are valid, both for class and method declaration:

```php
/**
 * @Transaction(connection="manager") 
 */

/**
 * @Transaction("manager") 
 */
```

Using the @Transaction annotation without arguments, as in the following example, is allowed and corresponds to use the default EntityManager.

```php
/**
  * @Transaction
  */
```

## TransactionListener priority

The TransactionListener's priority in the call chain is set to **1** to events
*kernel.controller*, *kernel.response* e *kernel.exception*. To change this value, in the Symfony settings specify as follows:

```yaml
parameters:
  symfonyboot.transaction.priority.controller: 2 #changing to 2
  symfonyboot.transaction.priority.response: 3 #changing to 3
  symfonyboot.transaction.priority.exception: 4 #changing to 4
```

**Important:**
- Symfony allows you to specify any positive, negative, or zero integer values.
- You can change the priority of any TransactionListener events individually and independently.
