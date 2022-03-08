<?php

namespace SymfonyBoot\SymfonyBootBundle\Rest\Annotation;

use SymfonyBoot\SymfonyBootBundle\Rest\Converter\BodyConverter;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Body extends Rest
{
    public function getConverter(): string
    {
        return BodyConverter::class;
    }
}
