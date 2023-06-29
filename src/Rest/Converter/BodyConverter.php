<?php

namespace SilasYudi\RestBootBundle\Rest\Converter;

use Symfony\Component\HttpFoundation\Request;

class BodyConverter extends AbstractConverter
{
    protected function getContent(Request $request): string
    {
        return $request->getContent();
    }
}
