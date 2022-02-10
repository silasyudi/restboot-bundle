<?php

namespace Tests\Rest\Converter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use SymfonyBoot\SymfonyBootBundle\Rest\Converter\BodyConverter;
use SymfonyBoot\SymfonyBootBundle\Rest\ValueObject\ConverterContext;
use SymfonyBoot\SymfonyBootBundle\Serializer\DefaultFormat;
use Tests\Rest\Util\Entity\JsonEntity;
use Tests\Rest\Util\Entity\Person;

class BodyConverterTest extends KernelTestCase
{

    private BodyConverter $bodyConverter;

    protected function setUp(): void
    {
        parent::bootKernel();
        $serializer = parent::$container->get('serializer');
        $this->bodyConverter = new BodyConverter($serializer, new DefaultFormat('json'));
    }

    public function testWithArrayWithKeysAndWithoutAddressAndBoolTrue()
    {
        $body = JsonEntity::getPersonWithArrayWithKeysAndWithoutAddressAndBoolTrue();
        $request = Request::create('/action', 'GET', [], [], [], [], $body);
        $context = new ConverterContext(Person::class, 'person');

        $this->bodyConverter->apply($request, $context);

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

    public function testWithArrayWithoutKeysAndWithAddressAndBoolFalseAndExplicitNullValue()
    {
        $body = JsonEntity::getPersonWithArrayWithoutKeysAndWithAddressAndBoolFalseAndExplicitNullValue();
        $request = Request::create('/action', 'GET', [], [], [], [], $body);
        $context = new ConverterContext(Person::class, 'person');

        $this->bodyConverter->apply($request, $context);

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
        $this->assertNull($address->getComplement());
    }
}
