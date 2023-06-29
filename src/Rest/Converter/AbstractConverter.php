<?php

namespace SilasYudi\RestBootBundle\Rest\Converter;

use JMS\Serializer\SerializerInterface;
use SilasYudi\RestBootBundle\Rest\ValueObject\ConverterContext;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractConverter
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    final public function apply(Request $request, ConverterContext $context): void
    {
        $entity = $this->serializer->deserialize(
            $this->getContent($request),
            $context->getEntityType(),
            'json'
        );
        $request->attributes->set($context->getEntityName(), $entity);
    }

    abstract protected function getContent(Request $request): string;
}
