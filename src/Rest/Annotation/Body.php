<?php

namespace SilasYudi\RestBootBundle\Rest\Annotation;

use SilasYudi\RestBootBundle\Rest\Converter\BodyConverter;

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
