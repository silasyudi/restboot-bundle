<?php

namespace SilasYudi\RestBootBundle\Rest\Converter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use SilasYudi\RestBootBundle\Rest\ValueObject\ConverterContext;
use SilasYudi\RestBootBundle\Serializer\DefaultFormat;

abstract class AbstractConverter
{

    protected SerializerInterface $serializer;
    protected DefaultFormat $defaultFormat;

    public function __construct(SerializerInterface $serializer, DefaultFormat $defaultFormat)
    {
        $this->serializer = $serializer;
        $this->defaultFormat = $defaultFormat;
    }

    final public function apply(Request $request, ConverterContext $context): void
    {
        $entity = $this->serializer->deserialize(
            $this->getContent($request),
            $context->getEntityType(),
            $this->getFormat($request),
            ['disable_type_enforcement' => true]
        );
        $request->attributes->set($context->getEntityName(), $entity);
    }

    abstract protected function getContent(Request $request): string;

    abstract protected function getFormat(Request $request): string;
}
