<?php

namespace Tests\Util\Controller\Rest;

use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;
use Tests\Util\Entity\Address;
use Tests\Util\Entity\Person;

class ControllerQuery
{

    /**
     * @Query(parameter="person")
     */
    public function __invoke(Person $person)
    {
    }

    /**
     * @Query("person")
     */
    public function withoutArgumentName(Person $person)
    {
    }

    /**
     * @Query(parameter="address")
     */
    public function withArgumentName(Address $address)
    {
    }

    /**
     * @Query("person")
     */
    public function withoutParameter()
    {
    }

    public function withoutAnnotation(Person $person)
    {
    }

    /**
     * @Query("person")
     */
    public function withoutTypeParameter($person)
    {
    }

    /**
     * @Query("person")
     * @Body("address")
     */
    public function withTwoAnnotations(Person $person, Address $address)
    {
    }
}
