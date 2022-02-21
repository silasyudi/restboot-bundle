<?php

namespace Tests\EventSubscriber;

use EmptyIterator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SymfonyBoot\SymfonyBootBundle\EventSubscriber\RestSubscriber;
use SymfonyBoot\SymfonyBootBundle\Exception\ConverterNotFoundException;
use SymfonyBoot\SymfonyBootBundle\Exception\NotTypedParameterException;
use SymfonyBoot\SymfonyBootBundle\Exception\ParameterNotFoundException;
use Tests\Util\Entity\Address;
use Tests\Util\Entity\Person;

abstract class RestSubscriberTest extends KernelTestCase
{
    protected RestSubscriber $restSubscriber;

    protected function setUp(): void
    {
        parent::bootKernel();
        $this->restSubscriber = parent::$container->get(RestSubscriber::class);
    }

    /**
     * @dataProvider providers
     */
    public function testScenarioArrayWithKeysAndDateTimeAndNotIssetSecondLevelObject(callable $controller): void
    {
        $request = $this->getRequest('ScenarioArrayWithKeysAndDateTimeAndNotIssetSecondLevelObject');
        $this->runEvent($controller, $request);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Silas', $person->getName());
        $this->assertEquals(30, $person->getAge());
        $this->assertTrue($person->isMale());
        $this->assertEquals('2000-01-01 12:34:56', $person->getBirtydate()->format('Y-m-d H:i:s'));
        $this->assertEquals('99999-9999', $person->getPhones()['personal']);
        $this->assertEquals('88888-8888', $person->getPhones()['work']);
        $this->assertNull($person->getAddress());
    }

    /**
     * @dataProvider providers
     */
    public function testScenarioArrayWithoutKeysAndSetSecondLevelObject(callable $controller): void
    {
        $request = $this->getRequest('ScenarioArrayWithoutKeysAndSetSecondLevelObject');
        $this->runEvent($controller, $request);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Carol', $person->getName());
        $this->assertEquals(30, $person->getAge());
        $this->assertFalse($person->isMale());
        $this->assertEquals('2000-01-01 12:34:56 -03:00', $person->getBirtydate()->format('Y-m-d H:i:s P'));
        $this->assertEquals(['99999-9999', '88888-8888'], $person->getPhones());

        $address = $person->getAddress();
        $this->assertEquals('Large Avenue', $address->getStreet());
        $this->assertEquals(123, $address->getNumber());
        $this->assertEmpty($address->getComplement());
    }

    public function providers(): array
    {
        return [
            [$this->getControllerInstance()],
            [[$this->getControllerInstance(), 'withoutArgumentName']],
        ];
    }

    public function testScenarioWithNotIssetProperty(): void
    {
        $request = $this->getRequest('ScenarioWithNotIssetProperty');
        $this->runEvent([$this->getControllerInstance(), 'withArgumentName'], $request);

        /** @var Address $address */
        $address = $request->get('address');
        $this->assertEquals('Large Avenue', $address->getStreet());
        $this->assertEquals(123, $address->getNumber());
        $this->assertNull($address->getComplement());
    }

    /**
     * @dataProvider providerTestErrorsScenarios
     */
    public function testErrorsScenariosShouldThrowExceptions(callable $controller, string $throwable): void
    {
        $this->expectException($throwable);

        $event = $this->getEvent($controller);
        $event->expects(self::never())->method('getRequest');

        $this->restSubscriber->onKernelController($event);
    }

    public function providerTestErrorsScenarios(): array
    {
        return [
            [[$this->getControllerInstance(), 'withoutParameter'], ParameterNotFoundException::class],
            [[$this->getControllerInstance(), 'withoutTypeParameter'], NotTypedParameterException::class],
        ];
    }

    public function testWithoutConvertersShouldThrowConverterNotFoundException(): void
    {
        $this->expectException(ConverterNotFoundException::class);

        $event = $this->getEvent($this->getControllerInstance());
        $event->expects(self::never())->method('getRequest');

        (new RestSubscriber(new EmptyIterator()))->onKernelController($event);
    }

    public function testWithoutAnnotationShouldDoNothing(): void
    {
        $event = $this->getEvent([$this->getControllerInstance(), 'withoutAnnotation']);
        $event->expects(self::never())->method('getRequest');

        $this->restSubscriber->onKernelController($event);
    }

    public function testSubscribedEventsShouldBeOnlyKernelController(): void
    {
        $this->assertEquals(
            [KernelEvents::CONTROLLER => 'onKernelController'],
            RestSubscriber::getSubscribedEvents()
        );
    }

    protected function runEvent(callable $controller, Request $request)
    {
        $event = $this->getEvent($controller);
        $event->expects(self::once())->method('getRequest')->willReturn($request);

        $this->restSubscriber->onKernelController($event);
    }

    /**
     * @param callable $controller
     * @return MockObject|ControllerEvent
     */
    protected function getEvent(callable $controller): MockObject
    {
        $event = $this->createMock(ControllerEvent::class);

        $event->expects(self::once())
            ->method('getController')
            ->willReturn($controller);

        return $event;
    }

    abstract protected function getControllerInstance();

    abstract protected function getRequest(string $file): Request;

}
