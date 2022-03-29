<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\Util\Controller\Transaction;

use SymfonyBoot\SymfonyBootBundle\Transaction\Annotation\Transaction;

class ControllerWithoutClassTransaction
{

    public function methodWithoutTransaction()
    {
    }

    /**
     * @Transaction("default")
     */
    public function methodWithTransactionWithArguments()
    {
    }

    /**
     * @Transaction(connection="default")
     */
    public function methodWithTransactionWithNameOfParameter()
    {
    }

    /**
     * @Transaction
     */
    public function methodWithTransactionWithoutArguments()
    {
    }
}
