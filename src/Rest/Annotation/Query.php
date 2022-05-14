<?php

namespace SilasYudi\RestBootBundle\Rest\Annotation;

use SilasYudi\RestBootBundle\Rest\Converter\QueryConverter;

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
