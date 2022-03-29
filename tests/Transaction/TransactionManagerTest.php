<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\Transaction;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SymfonyBoot\SymfonyBootBundle\Transaction\TransactionManager;

class TransactionManagerTest extends TestCase
{

    private TransactionManager $transactionManager;
    private Connection $connection;

    public function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->transactionManager = new TransactionManager($this->connection);
    }

    public function testBeginShouldDoBeginTransaction(): void
    {
        $this->connection->expects(self::once())->method('beginTransaction');
        $this->connection->expects(self::never())->method('isTransactionActive');
        $this->connection->expects(self::never())->method('commit');
        $this->connection->expects(self::never())->method('isRollbackOnly');
        $this->connection->expects(self::never())->method('rollBack');

        $this->transactionManager->begin();
    }

    public function testCommitWithTransactionActiveAndNotRollbackOnlyShouldDoCommit(): void
    {
        $this->connection->expects(self::never())->method('beginTransaction');
        $this->connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $this->connection->expects(self::once())->method('commit');
        $this->connection->expects(self::once())->method('isRollbackOnly')->willReturn(false);
        $this->connection->expects(self::never())->method('rollBack');

        $this->transactionManager->commit();
    }

    public function testCommitWithTransactionActiveAndRollBackOnlyShouldDoNothing(): void
    {
        $this->connection->expects(self::never())->method('beginTransaction');
        $this->connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $this->connection->expects(self::never())->method('commit');
        $this->connection->expects(self::once())->method('isRollbackOnly')->willReturn(true);
        $this->connection->expects(self::never())->method('rollBack');

        $this->transactionManager->commit();
    }

    public function testCommitWithTransactionInactiveShouldDoNothing(): void
    {
        $this->connection->expects(self::never())->method('beginTransaction');
        $this->connection->expects(self::once())->method('isTransactionActive')->willReturn(false);
        $this->connection->expects(self::never())->method('commit');
        $this->connection->expects(self::never())->method('isRollbackOnly');
        $this->connection->expects(self::never())->method('rollBack');

        $this->transactionManager->commit();
    }

    public function testRollbackWithTransactionActiveShouldDoRollback(): void
    {
        $this->connection->expects(self::never())->method('beginTransaction');
        $this->connection->expects(self::once())->method('isTransactionActive')->willReturn(true);
        $this->connection->expects(self::never())->method('commit');
        $this->connection->expects(self::never())->method('isRollbackOnly');
        $this->connection->expects(self::once())->method('rollBack');

        $this->transactionManager->rollback();
    }

    public function testRollbackWithTransactionInactiveShouldDoNothing(): void
    {
        $this->connection->expects(self::never())->method('beginTransaction');
        $this->connection->expects(self::once())->method('isTransactionActive')->willReturn(false);
        $this->connection->expects(self::never())->method('commit');
        $this->connection->expects(self::never())->method('isRollbackOnly');
        $this->connection->expects(self::never())->method('rollBack');

        $this->transactionManager->rollback();
    }

    public function testGetConnectionShouldReturnsConnection(): void
    {
        $this->assertSame($this->connection, $this->transactionManager->getConnection());
    }
}
