<?php

namespace SymfonyBoot\SymfonyBootBundle\Rest\Converter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use SymfonyBoot\SymfonyBootBundle\Serializer\DefaultFormat;

class QueryConverter extends AbstractConverter
{

    public function __construct(SerializerInterface $serializer)
    {
        parent::__construct($serializer, new DefaultFormat('json'));
    }

    protected function getContent(Request $request): string
    {
        return json_encode($request->query->all());
    }

    protected function getFormat(Request $request): string
    {
        return $this->defaultFormat->getFormat();
    }
}
