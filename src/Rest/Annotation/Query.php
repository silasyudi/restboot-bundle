<?php

namespace SymfonyBoot\SymfonyBootBundle\Rest\Annotation;

use SymfonyBoot\SymfonyBootBundle\Rest\Converter\QueryConverter;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Query extends Rest
{
    public function getConverter(): string
    {
        return QueryConverter::class;
    }
}
