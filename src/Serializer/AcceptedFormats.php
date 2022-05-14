<?php

namespace SilasYudi\RestBootBundle\Serializer;

class AcceptedFormats
{
    private array $formats;

    public function __construct(array $formats)
    {
        $this->formats = $formats;
    }

    public function isAcceptedFormat(string $format): bool
    {
        return in_array($format, $this->formats);
    }
}
