<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Controller\Transaction;

use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;

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
