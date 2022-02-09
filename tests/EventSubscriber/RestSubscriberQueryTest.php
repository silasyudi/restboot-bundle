<?php

namespace Tests\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\MoreThanOneRestParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\NotTypedParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\ParameterNotFoundException;
use Tests\Rest\Util\Controller\ControllerQuery;
use Tests\Rest\Util\Entity\QueryEntity;

class RestSubscriberQueryTest extends RestSubscriberTest
{

    protected function getRequest(): Request
    {
        return Request::create('/' . QueryEntity::getAddressWithoutComplement());
    }

    public function providerRest(): array
    {
        return [
            [new ControllerQuery()],
            [[new ControllerQuery(), 'withoutArgumentName']],
        ];
    }

    public function providerErrors(): array
    {
        return [
            [[new ControllerQuery(), 'withoutParameter'], ParameterNotFoundException::class],
            [[new ControllerQuery(), 'withoutTypeParameter'], NotTypedParameterException::class],
            [[new ControllerQuery(), 'withoutTwoAnnotations'], MoreThanOneRestParameterException::class],
        ];
    }
}
