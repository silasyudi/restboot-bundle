<?php

namespace SymfonyBoot\SymfonyBootBundle\Rest\Converter;

use Symfony\Component\HttpFoundation\Request;

class BodyConverter extends AbstractConverter
{

    protected function getContent(Request $request): string
    {
        return $request->getContent();
    }

    protected function getFormat(Request $request): ?string
    {
        return $request->getContentType() ?: $this->defaultFormat->getFormat();
    }
}
