<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\EventListener\Rest;

use Symfony\Component\HttpFoundation\Request;
use SymfonyBoot\SymfonyBootBundle\Tests\EventListener\RestListenerTest;
use SymfonyBoot\SymfonyBootBundle\Tests\Util\Controller\Rest\ControllerQuery;
use SymfonyBoot\SymfonyBootBundle\Tests\Util\Payloads\FileReaderUtil;

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
