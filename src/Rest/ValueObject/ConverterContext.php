<?php

namespace SymfonyBoot\SymfonyBootBundle\Rest\ValueObject;

class ConverterContext
{

    private string $entityType;
    private string $entityName;

    public function __construct(string $entityType, string $entityName)
    {
        $this->entityType = $entityType;
        $this->entityName = $entityName;
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }
}
