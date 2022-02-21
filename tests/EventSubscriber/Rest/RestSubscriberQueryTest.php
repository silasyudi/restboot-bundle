<?php

namespace Tests\EventSubscriber\Rest;

use Symfony\Component\HttpFoundation\Request;
use Tests\EventSubscriber\RestSubscriberTest;
use Tests\Util\Controller\Rest\ControllerQuery;
use Tests\Util\Payloads\FileReaderUtil;

class RestSubscriberQueryTest extends RestSubscriberTest
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
