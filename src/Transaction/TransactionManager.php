<?php

namespace SymfonyBoot\SymfonyBootBundle\Transaction;

use Doctrine\DBAL\Connection;

class TransactionManager
{

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function begin(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->commit();
        }
    }

    public function rollback(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
