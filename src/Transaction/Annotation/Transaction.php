<?php

namespace SilasYudi\RestBootBundle\Transaction\Annotation;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Transaction
{

    private ?string $connection;

    public function __construct(array $value)
    {
        $this->connection = $value['connection'] ?? $value['value'] ?? null;
    }

    public function getConnection(): ?string
    {
        return $this->connection;
    }
}
