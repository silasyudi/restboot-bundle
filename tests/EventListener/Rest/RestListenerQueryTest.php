<?php

namespace Tests\EventListener\Rest;

use Symfony\Component\HttpFoundation\Request;
use Tests\EventListener\RestListenerTest;
use Tests\Util\Controller\Rest\ControllerQuery;
use Tests\Util\Payloads\FileReaderUtil;

class RestListenerQueryTest extends RestListenerTest
{

    protected function getControllerInstance(): ControllerQuery
    {
        return new ControllerQuery();
    }

    protected function getRequest(string $file): Request
    {
        return Request::create('/' . FileReaderUtil::readFile('query/' . $file . '.txt'));
    }
}
