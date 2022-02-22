<?php

namespace Tests\EventSubscriber;

use EmptyIterator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
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
        $this->restSubscriber = parent::$kernel->getContainer()->get(RestSubscriber::class);
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

        $request = $this->getRequestDefault();
        $event = $this->getEvent($controller, $request);

        $this->restSubscriber->onKernelController($event);

        $this->assertEmpty($request->attributes);
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

        $request = $this->getRequestDefault();
        $event = $this->getEvent($this->getControllerInstance(), $this->getRequestDefault());

        (new RestSubscriber(new EmptyIterator()))->onKernelController($event);
        $this->assertEmpty($request->attributes);
    }

    public function testWithoutAnnotationShouldDoNothing(): void
    {
        $request = $this->getRequestDefault();
        $event = $this->getEvent([$this->getControllerInstance(), 'withoutAnnotation'], $request);

        $this->restSubscriber->onKernelController($event);

        $this->assertEmpty($request->attributes);
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
        $event = $this->getEvent($controller, $request);

        $this->restSubscriber->onKernelController($event);
    }

    protected function getEvent(callable $controller, Request $request): ControllerEvent
    {
        return new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            $controller,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }

    abstract protected function getControllerInstance();

    abstract protected function getRequest(string $file): Request;

    protected function getRequestDefault(): Request
    {
        return Request::create('/');
    }
}
