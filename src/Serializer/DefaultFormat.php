<?php

namespace SilasYudi\RestBootBundle\Serializer;

class DefaultFormat
{
    private string $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
