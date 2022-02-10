<?php

namespace Tests\EventSubscriber;

use EmptyIterator;
use Symfony\Component\HttpFoundation\Request;
use SymfonyBoot\SymfonyBootBundle\EventSubscriber\RestSubscriber;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\ConverterNotFoundException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\MoreThanOneRestParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\NotTypedParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\ParameterNotFoundException;
use Tests\Rest\Util\Controller\ControllerBody;
use Tests\Rest\Util\Entity\JsonEntity;

class RestSubscriberBodyTest extends RestSubscriberTest
{

    protected function getRequest(): Request
    {
        return Request::create('/', 'GET', [], [], [], [], JsonEntity::getAddressWithoutComplement());
    }

    public function providerRest(): array
    {
        return [
            [new ControllerBody()],
            [[new ControllerBody(), 'withoutArgumentName']],
            [[new ControllerBody(), 'withOthersAnnotations']],
        ];
    }

    public function providerErrors(): array
    {
        return [
            [[new ControllerBody(), 'withoutParameter'], ParameterNotFoundException::class],
            [[new ControllerBody(), 'withoutTypeParameter'], NotTypedParameterException::class],
            [[new ControllerBody(), 'withTwoAnnotations'], MoreThanOneRestParameterException::class],
        ];
    }

    public function testWithoutConverters(): void
    {
        $this->expectException(ConverterNotFoundException::class);

        $event = $this->getEvent(new ControllerBody());
        $event->expects(self::never())->method('getRequest');

        (new RestSubscriber(new EmptyIterator()))->onKernelController($event);
    }

    public function testWithoutAnnotation(): void
    {
        $event = $this->getEvent([new ControllerBody(), 'withoutAnnotation']);
        $event->expects(self::never())->method('getRequest');

        $this->restSubscriber->onKernelController($event);
    }
}
