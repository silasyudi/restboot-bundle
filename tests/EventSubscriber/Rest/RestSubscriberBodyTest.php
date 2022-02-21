<?php

namespace Tests\EventSubscriber\Rest;

use Symfony\Component\HttpFoundation\Request;
use Tests\EventSubscriber\RestSubscriberTest;
use Tests\Util\Controller\Rest\ControllerBody;
use Tests\Util\Payloads\FileReaderUtil;

class RestSubscriberBodyTest extends RestSubscriberTest
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
