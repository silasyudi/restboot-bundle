<?php

namespace Tests\EventListener\Rest;

use Symfony\Component\HttpFoundation\Request;
use Tests\EventListener\RestListenerTest;
use Tests\Util\Controller\Rest\ControllerBody;
use Tests\Util\Payloads\FileReaderUtil;

class RestListenerBodyTest extends RestListenerTest
{

    protected function getControllerInstance(): ControllerBody
    {
        return new ControllerBody();
    }

    protected function getRequest(string $file): Request
    {
        return Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            FileReaderUtil::readFile('body/' . $file . '.json')
        );
    }
}
