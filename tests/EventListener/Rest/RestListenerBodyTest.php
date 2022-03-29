<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\EventListener\Rest;

use Symfony\Component\HttpFoundation\Request;
use SymfonyBoot\SymfonyBootBundle\Tests\EventListener\RestListenerTest;
use SymfonyBoot\SymfonyBootBundle\Tests\Util\Controller\Rest\ControllerBody;
use SymfonyBoot\SymfonyBootBundle\Tests\Util\Entity\Address;
use SymfonyBoot\SymfonyBootBundle\Tests\Util\Payloads\FileReaderUtil;

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
}
