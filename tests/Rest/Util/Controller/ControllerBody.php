<?php

namespace Tests\Rest\Util\Controller;

use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;
use Tests\Rest\Util\Annotation\OtherAnnotation;
use Tests\Rest\Util\Entity\Address;

class ControllerBody
{

    /**
     * @Body(parameter="address")
     */
    public function __invoke(Address $address)
    {
    }

    /**
     * @Body("address")
     */
    public function withoutArgumentName(Address $address)
    {
    }

    /**
     * @Body("address")
     */
    public function withoutParameter()
    {
    }

    public function withoutAnnotation(Address $address)
    {
    }

    /**
     * @Body("address")
     */
    public function withoutTypeParameter($address)
    {
    }

    /**
     * @Body("address1")
     * @Query("address2")
     */
    public function withTwoAnnotations(Address $address1, Address $address2)
    {
    }

    /**
     * @OtherAnnotation
     * @Body("address")
     */
    public function withOthersAnnotations(Address $address)
    {
    }
}
