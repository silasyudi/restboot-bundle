<?php

namespace SilasYudi\RestBootBundle\Tests\EventListener\Rest;

use SilasYudi\RestBootBundle\Tests\Util\Entity\Primitives;
use Symfony\Component\HttpFoundation\Request;
use SilasYudi\RestBootBundle\Tests\EventListener\RestListenerTest;
use SilasYudi\RestBootBundle\Tests\Util\Controller\Rest\ControllerBody;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Address;
use SilasYudi\RestBootBundle\Tests\Util\Payloads\FileReaderUtil;

class RestListenerBodyTest extends RestListenerTest
{

    protected function getControllerInstance(): ControllerBody
    {
        return new ControllerBody();
    }

    protected function getRequest(string $file): Request
    {
        return Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            FileReaderUtil::readFile('body/' . $file . '.json')
        );
    }

    public function testScenarioWithoutSupportedContentTypeShouldGetDefaultContentType(): void
    {
        $request = Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'text/plain'],
            FileReaderUtil::readFile('body/ScenarioWithNotIssetProperty.json')
        );
        $this->runEvent([$this->getControllerInstance(), 'withArgumentName'], $request);

        /** @var Address $address */
        $address = $request->get('address');
        $this->assertEquals('Large Avenue', $address->getStreet());
        $this->assertEquals(123, $address->getNumber());
        $this->assertNull($address->getComplement());
    }

    public function testPrimitives(): void
    {
        $request = $this->getRequest('TestPrimitives');
        $this->runEvent([$this->getControllerInstance(), 'primitives'], $request);

        /** @var Primitives $primitives */
        $primitives = $request->get('primitives');
        $this->assertFalse($primitives->isBoolStringVazia());
        $this->assertFalse($primitives->isBoolStringZero());
        $this->assertTrue($primitives->isBoolStringInteiroPositivo());
        $this->assertTrue($primitives->isBoolStringInteiroNegativo());
        $this->assertTrue($primitives->isBoolStringNaoVazia());
        $this->assertEquals('', $primitives->getStringVazia());
        $this->assertEquals('a', $primitives->getStringNaoVazia());
        $this->assertNull($primitives->getNulo());
        $this->assertNull($primitives->getNotIsSet());
        $this->assertEquals(1, $primitives->getPositivo());
        $this->assertEquals(-1, $primitives->getNegativo());
        $this->assertEquals(1, $primitives->getStringPositivo());
        $this->assertEquals(-1, $primitives->getStringNegativo());
        $this->assertEquals(1.2, $primitives->getFloatPositivo());
        $this->assertEquals(-1.2, $primitives->getFloatNegativo());
        $this->assertEquals(1.2, $primitives->getFloatStringPositivo());
        $this->assertEquals(-1.2, $primitives->getFloatStringNegativo());
        $this->assertEmpty($primitives->getArrayVazio());
    }
}
