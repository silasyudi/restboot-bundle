<?php

namespace Tests\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use SymfonyBoot\SymfonyBootBundle\EventListener\TransactionListener;
use Tests\Util\Controller\Transaction\ControllerWithClassTransaction;
use Tests\Util\Controller\Transaction\ControllerWithoutClassTransaction;

class TransactionListenerTest extends TestCase
{
    protected TransactionListener $transactionListener;
    protected ManagerRegistry $managerRegistry;

    protected function setUp(): void
    {
        $this->managerRegistry = $this->createMock(ManagerRegistry::class);
        $this->transactionListener = new TransactionListener($this->managerRegistry);
    }

    public function providersForTestControllersWithTransactions(): array
    {
        return [
            [new ControllerWithClassTransaction(), 'another'],
            [[new ControllerWithClassTransaction(), 'methodWithoutTransaction'], 'default'],
            [[new ControllerWithoutClassTransaction(), 'methodWithTransactionWithArguments'], 'default'],
            [[new ControllerWithoutClassTransaction(), 'methodWithTransactionWithNameOfParameter'], 'default'],
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
        $connection->expects(self::once())->method('isRollbackOnly')->willReturn(false);
        $connection->expects(self::never())->method('rollback');

        $this->runControllerEvent($controller, $connection, $connectionName);
        $this->runResponseEvent();
    }

    /**
     * @dataProvider providersForTestControllersWithTransactions
     */
    public function testResponseEventWithTransactionActiveRollbackOnlyDoNothing(
        callable $controller,
        ?string $connectionName
    ) {
        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $connection->expects(self::once())->method('beginTransaction');
        $connection->expects(self::never())->method('commit');
        $connection->expects(self::once())->method('isRollbackOnly')->willReturn(true);
        $connection->expects(self::never())->method('rollback');

        $this->runControllerEvent($controller, $connection, $connectionName);
        $this->runResponseEvent();
    }

    public function testResponseEventWithTransactionInactiveDoNothing(): void
    {
        $this->managerRegistry->expects(self::never())->method('getConnection');
        $event = $this->getControllerEvent($this->getControllerWithoutTransaction());

        $this->transactionListener->onKernelController($event);
        $this->runResponseEvent();
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
        $connection->expects(self::never())->method('isRollbackOnly');
        $connection->expects(self::once())->method('rollback');

        $this->runControllerEvent($controller, $connection, $connectionName);
        $this->runExceptionEvent();
    }

    public function testExceptionEventWithTransactionInactiveShouldDoNothing(): void
    {
        $this->managerRegistry->expects(self::never())->method('getConnection');
        $event = $this->getControllerEvent($this->getControllerWithoutTransaction());

        $this->transactionListener->onKernelController($event);
        $this->runExceptionEvent();
    }

    private function runControllerEvent(callable $controller, Connection $connection, ?string $connectionName): void
    {
        $this->managerRegistry->expects(self::once())
            ->method('getConnection')
            ->with($connectionName)
            ->willReturn($connection);

        $event = $this->getControllerEvent($controller);

        $this->transactionListener->onKernelController($event);
    }

    private function getControllerEvent(callable $controller): ControllerEvent
    {
        return new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            $controller,
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST
        );
    }

    private function runResponseEvent(): void
    {
        $event = new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            $this->createMock(Response::class)
        );
        $this->transactionListener->onKernelResponse($event);
    }

    private function runExceptionEvent(): void
    {
        $event = new ExceptionEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->createMock(Request::class),
            HttpKernelInterface::MASTER_REQUEST,
            new Exception()
        );
        $this->transactionListener->onKernelException($event);
    }
}
