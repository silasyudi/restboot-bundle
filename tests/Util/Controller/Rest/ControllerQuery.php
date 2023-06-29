<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Controller\Rest;

use SilasYudi\RestBootBundle\Rest\Annotation\Body;
use SilasYudi\RestBootBundle\Rest\Annotation\Query;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Address;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Person;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Primitives;

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

    /**
     * @Query(parameter="primitives")
     */
    public function primitives(Primitives $primitives)
    {
    }
}
