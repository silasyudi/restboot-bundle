<?php

namespace Tests\Rest\Converter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use SymfonyBoot\SymfonyBootBundle\Rest\Converter\QueryConverter;
use SymfonyBoot\SymfonyBootBundle\Rest\ValueObject\ConverterContext;
use Tests\Rest\Util\Entity\Person;
use Tests\Rest\Util\Entity\QueryEntity;

class QueryConverterTest extends KernelTestCase
{

    private QueryConverter $queryConverter;

    protected function setUp(): void
    {
        parent::bootKernel();
        $serializer = parent::$container->get('serializer');
        $this->queryConverter = new QueryConverter($serializer);
    }

    public function testWithArrayWithKeysAndWithoutAddressAndBoolTrue()
    {
        $query = QueryEntity::getPersonWithArrayWithKeysAndWithoutAddressAndBoolCastFromOne();
        $request = Request::create('/action' . $query);
        $context = new ConverterContext(Person::class, 'person');

        $this->queryConverter->apply($request, $context);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Silas', $person->getName());
        $this->assertEquals(30, $person->getAge());
        $this->assertTrue($person->isMale());
        $this->assertEquals('2000-01-01', $person->getBirtydate()->format('Y-m-d'));
        $this->assertEquals('99999-9999', $person->getPhones()['personal']);
        $this->assertEquals('88888-8888', $person->getPhones()['work']);
        $this->assertNull($person->getAddress());
    }

    public function testWithArrayWithoutKeysAndWithAddressAndBoolFalseAndExplicitEmptyValue()
    {
        $query = QueryEntity::getPersonWithArrayWithoutKeysAndWithAddressAndBoolCastFromZeroAndExplicitEmptyValue();
        $request = Request::create('/action' . $query);
        $context = new ConverterContext(Person::class, 'person');

        $this->queryConverter->apply($request, $context);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Carol', $person->getName());
        $this->assertEquals(30, $person->getAge());
        $this->assertFalse($person->isMale());
        $this->assertEquals('2000-01-01', $person->getBirtydate()->format('Y-m-d'));
        $this->assertEquals(['99999-9999', '88888-8888'], $person->getPhones());

        $address = $person->getAddress();
        $this->assertEquals('Large Avenue', $address->getStreet());
        $this->assertEquals(123, $address->getNumber());
        $this->assertEmpty($address->getComplement());
    }
}
