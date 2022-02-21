<?php

namespace Tests\EventSubscriber;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SymfonyBoot\SymfonyBootBundle\EventSubscriber\TransactionSubscriber;
use Tests\Util\Controller\Transaction\ControllerWithClassTransaction;
use Tests\Util\Controller\Transaction\ControllerWithoutClassTransaction;

class TransactionSubscriberTest extends TestCase
{
    protected TransactionSubscriber $transactionSubscriber;
    protected ManagerRegistry $managerRegistry;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->transactionSubscriber = new TransactionSubscriber($this->managerRegistry);
    }

    public function providersForTestControllersWithTransactions(): array
    {
        return [
            [new ControllerWithClassTransaction(), 'another'],
            [[new ControllerWithClassTransaction(), 'methodWithoutTransaction'], 'default'],
            [[new ControllerWithoutClassTransaction(), 'methodWithTransactionWithArguments'], 'default'],
            [[new ControllerWithoutClassTransaction(), 'methodWithTransactionWithoutArguments'], null],
        ];
    }

    public function getControllerWithoutTransaction(): array
    {
        return [new ControllerWithoutClassTransaction(), 'methodWithoutTransaction'];
    }

    /**
     * @dataProvider providersForTestControllersWithTransactions
     */
    public function testResponseEventWithTransactionActiveShouldCommit(callable $controller, ?string $connectionName)
    {
        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $connection->expects(self::once())->method('beginTransaction');
        $connection->expects(self::once())->method('commit');
        $connection->expects(self::never())->method('rollback');

        $this->runControllerEvent($controller, $connection, $connectionName);
        $this->runResponseEvent();
    }

    public function testResponseEventWithTransactionInactiveDoNothing(): void
    {
        $this->managerRegistry->expects(self::never())->method('getConnection');

        $event = $this->createMock(ControllerEvent::class);
        $event->expects(self::once())
            ->method('getController')
            ->willReturn($this->getControllerWithoutTransaction());

        $this->transactionSubscriber->onKernelController($event);
        $this->transactionSubscriber->onKernelResponse($this->createMock(ResponseEvent::class));
    }

    /**
     * @dataProvider providersForTestControllersWithTransactions
     */
    public function testExceptionEventWithTransactionActiveShouldRollback(callable $controller, ?string $connectionName)
    {
        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $connection->expects(self::once())->method('beginTransaction');
        $connection->expects(self::never())->method('commit');
        $connection->expects(self::once())->method('rollback');

        $this->runControllerEvent($controller, $connection, $connectionName);
        $this->runExceptionEvent();
    }

    public function testExceptionEventWithTransactionInactiveShouldDoNothing(): void
    {
        $this->managerRegistry->expects(self::never())->method('getConnection');

        $event = $this->createMock(ControllerEvent::class);
        $event->expects(self::once())
            ->method('getController')
            ->willReturn($this->getControllerWithoutTransaction());

        $this->transactionSubscriber->onKernelController($event);
        $this->transactionSubscriber->onKernelResponse($this->createMock(ResponseEvent::class));
    }

    private function runControllerEvent(callable $controller, Connection $connection, ?string $connectionName): void
    {
        $this->managerRegistry->expects(self::once())
            ->method('getConnection')
            ->with($connectionName)
            ->willReturn($connection);

        $event = $this->createMock(ControllerEvent::class);
        $event->expects(self::once())
            ->method('getController')
            ->with()
            ->willReturn($controller);

        $this->transactionSubscriber->onKernelController($event);
    }

    private function runResponseEvent(): void
    {
        $event = $this->createMock(ResponseEvent::class);
        $this->transactionSubscriber->onKernelResponse($event);
    }

    private function runExceptionEvent(): void
    {
        $event = $this->createMock(ExceptionEvent::class);
        $this->transactionSubscriber->onKernelException($event);
    }

    public function testSubscribedEventsShouldBeOnlyKernelController(): void
    {
        $this->assertEquals(
            [
                KernelEvents::CONTROLLER => 'onKernelController',
                KernelEvents::RESPONSE => 'onKernelResponse',
                KernelEvents::EXCEPTION => 'onKernelException',
            ],
            TransactionSubscriber::getSubscribedEvents()
        );
    }
}
