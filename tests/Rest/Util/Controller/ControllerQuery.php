<?php

namespace Tests\Rest\Util\Controller;

use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;
use Tests\Rest\Util\Entity\Address;

class ControllerQuery
{

    /**
     * @Query(parameter="address")
     */
    public function __invoke(Address $address)
    {
    }

    /**
     * @Query("address")
     */
    public function withoutArgumentName(Address $address)
    {
    }

    /**
     * @Query("address")
     */
    public function withoutParameter()
    {
    }

    /**
     * @Query("address")
     */
    public function withoutTypeParameter($address)
    {
    }

    /**
     * @Query("address1")
     * @Body("address2")
     */
    public function withoutTwoAnnotations(Address $address1, Address $address2)
    {
    }
}
