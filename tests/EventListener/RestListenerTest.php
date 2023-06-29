<?php

namespace SilasYudi\RestBootBundle\Tests\EventListener;

use EmptyIterator;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Primitives;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use SilasYudi\RestBootBundle\EventListener\RestListener;
use SilasYudi\RestBootBundle\Exception\ConverterNotFoundException;
use SilasYudi\RestBootBundle\Exception\NotTypedParameterException;
use SilasYudi\RestBootBundle\Exception\ParameterNotFoundException;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Address;
use SilasYudi\RestBootBundle\Tests\Util\Entity\Person;

abstract class RestListenerTest extends KernelTestCase
{
    protected RestListener $restListener;

    protected function setUp(): void
    {
        parent::bootKernel();
        $this->restListener = parent::$kernel->getContainer()->get(RestListener::class);
    }

    /**
     * @dataProvider providers
     */
    public function testScenarioPrimitivesArrayAndObjectsArray(callable $controller): void
    {
        $request = $this->getRequest('ScenarioPrimitivesArrayAndObjectsArray');
        $this->runEvent($controller, $request);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Silas', $person->getName());
        $this->assertEquals(['Ninja', 'Mestre'], $person->getNicknames());
        $this->assertEquals(30, $person->getAge());
        $this->assertTrue($person->isMale());
        $this->assertEquals('2000-01-01 12:34:56', $person->getBirthdate()->format('Y-m-d H:i:s'));
        $this->assertEquals(65.432, $person->getWeight());
        $this->assertEquals('personal', $person->getPhones()[0]->getName());
        $this->assertEquals('99999-9999', $person->getPhones()[0]->getNumber());
        $this->assertEquals('work', $person->getPhones()[1]->getName());
        $this->assertEquals('88888-8888', $person->getPhones()[1]->getNumber());
        $this->assertEquals(-12, $person->getGameScore()->getBalanceWinningsLosses());
        $this->assertEquals(-1234.56, $person->getGameScore()->getBalancePoints());
        $this->assertNull($person->getAddress());
    }

    /**
     * @dataProvider providers
     */
    public function testScenarioSetSecondLevelObject(callable $controller): void
    {
        $request = $this->getRequest('ScenarioSetSecondLevelObject');
        $this->runEvent($controller, $request);

        /** @var Person $person */
        $person = $request->get('person');
        $this->assertEquals('Carol', $person->getName());
        $this->assertEquals(30, $person->getAge());
        $this->assertFalse($person->isMale());
        $this->assertEquals('2000-01-01 12:34:56', $person->getBirthdate()->format('Y-m-d H:i:s'));
        $this->assertEquals(65.432, $person->getWeight());
        $this->assertEquals(-12, $person->getGameScore()->getBalanceWinningsLosses());
        $this->assertEquals(-1234.56, $person->getGameScore()->getBalancePoints());

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

        $this->restListener->onKernelController($event);

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

        (new RestListener(new EmptyIterator()))->onKernelController($event);
        $this->assertEmpty($request->attributes);
    }

    public function testWithoutAnnotationShouldDoNothing(): void
    {
        $request = $this->getRequestDefault();
        $event = $this->getEvent([$this->getControllerInstance(), 'withoutAnnotation'], $request);

        $this->restListener->onKernelController($event);

        $this->assertEmpty($request->attributes);
    }

    protected function runEvent(callable $controller, Request $request)
    {
        $event = $this->getEvent($controller, $request);

        $this->restListener->onKernelController($event);
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
