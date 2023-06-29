<?php

namespace SilasYudi\RestBootBundle\Rest\Converter;

use Symfony\Component\HttpFoundation\Request;

class QueryConverter extends AbstractConverter
{
    protected function getContent(Request $request): string
    {
        return json_encode($request->query->all());
    }
}
