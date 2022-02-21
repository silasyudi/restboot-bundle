<?php

namespace Tests\Util\Controller\Rest;

use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;
use Tests\Util\Entity\Address;
use Tests\Util\Entity\Person;

class ControllerBody
{

    /**
     * @Body(parameter="person")
     */
    public function __invoke(Person $person)
    {
    }

    /**
     * @Body("person")
     */
    public function withoutArgumentName(Person $person)
    {
    }

    /**
     * @Body(parameter="address")
     */
    public function withArgumentName(Address $address)
    {
    }

    /**
     * @Body("person")
     */
    public function withoutParameter()
    {
    }

    public function withoutAnnotation(Person $person)
    {
    }

    /**
     * @Body("person")
     */
    public function withoutTypeParameter($person)
    {
    }

    /**
     * @Body("person")
     * @Query("address")
     */
    public function withTwoAnnotations(Person $person, Address $address)
    {
    }
}
