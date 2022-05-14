<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Controller\Transaction;

use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;

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
