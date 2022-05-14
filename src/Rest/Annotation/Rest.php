<?php

namespace SilasYudi\RestBootBundle\Rest\Annotation;

abstract class Rest
{
    /**
     * @Required
     */
    private string $parameter;

    public function __construct(array $value)
    {
        $this->parameter = $value['parameter'] ?? $value['value'];
    }

    final public function getParameter(): string
    {
        return $this->parameter;
    }

    abstract public function getConverter(): string;
}
