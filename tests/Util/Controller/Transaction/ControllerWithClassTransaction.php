<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\Util\Controller\Transaction;

use SymfonyBoot\SymfonyBootBundle\Transaction\Annotation\Transaction;

/**
 * @Transaction("default")
 */
class ControllerWithClassTransaction
{

    /**
     * @Transaction("another")
     */
    public function __invoke()
    {
    }

    public function methodWithoutTransaction()
    {
    }
}
