<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Controller\Rest;

use SilasYudi\RestBootBundle\Rest\Annotation\Body;
use SilasYudi\RestBootBundle\Rest\Annotation\Query;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Address;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Person;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Primitives;

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

    /**
     * @Body(parameter="primitives")
     */
    public function primitives(Primitives $primitives)
    {
    }
}
