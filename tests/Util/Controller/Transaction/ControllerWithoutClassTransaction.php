<?php

namespace Tests\Util\Controller\Transaction;

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
     * @Transaction
     */
    public function methodWithTransactionWithoutArguments()
    {
    }
}
