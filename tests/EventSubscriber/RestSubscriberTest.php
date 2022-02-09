<?php

namespace Tests\EventSubscriber;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SymfonyBoot\SymfonyBootBundle\EventSubscriber\RestSubscriber;
use Tests\Rest\Util\Entity\Address;

abstract class RestSubscriberTest extends KernelTestCase
{
    protected RestSubscriber $restSubscriber;

    protected function setUp(): void
    {
        parent::bootKernel();
        $this->restSubscriber = parent::$container->get(RestSubscriber::class);
    }

    protected function getEvent(callable $controller): MockObject
    {
        $event = $this->createMock(ControllerEvent::class);

        $event->expects(self::once())
            ->method('getController')
            ->willReturn($controller);

        return $event;
    }

    abstract protected function getRequest(): Request;

    /**
     * @dataProvider providerRest
     */
    public function testConverters(callable $controller): void
    {
        $event = $this->getEvent($controller);

        $request = $this->getRequest();
        $event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);

        $this->restSubscriber->onKernelController($event);

        $address = $request->attributes->get('address');
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Large Avenue', $address->getStreet());
        $this->assertEquals(123, $address->getNumber());
        $this->assertNull($address->getComplement());
    }

    abstract public function providerRest(): array;

    /**
     * @dataProvider providerErrors
     */
    public function testErrorsScenarios(callable $controller, string $throwable): void
    {
        $this->expectException($throwable);

        $event = $this->getEvent($controller);
        $event->expects(self::never())->method('getRequest');

        $this->restSubscriber->onKernelController($event);
    }

    abstract public function providerErrors(): array;

    public function testSubscribedEventsShouldBeOnlyKernelController(): void
    {
        $this->assertEquals(
            [KernelEvents::CONTROLLER => 'onKernelController'],
            RestSubscriber::getSubscribedEvents()
        );
    }
}
