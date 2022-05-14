<?php

namespace SilasYudi\RestBootBundle\Tests\EventListener\Rest;

use Symfony\Component\HttpFoundation\Request;
use SilasYudi\RestBootBundle\Tests\EventListener\RestListenerTest;
use SilasYudi\RestBootBundle\Tests\Util\Controller\Rest\ControllerQuery;
use SilasYudi\RestBootBundle\Tests\Util\Payloads\FileReaderUtil;

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
