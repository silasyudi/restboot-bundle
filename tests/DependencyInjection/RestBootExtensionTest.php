<?php

namespace SilasYudi\RestBootBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use SilasYudi\RestBootBundle\DependencyInjection\RestBootExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RestBootExtensionTest extends TestCase
{

    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        (new RestBootExtension())->load([], $container);
        $this->assertEquals('json', $container->getParameter('restboot.rest.payload.format'));
        $this->assertEquals(['json', 'xml'], $container->getParameter('restboot.rest.payload.accepted.formats'));
        $this->assertEquals(1, $container->getParameter('restboot.rest.priority.controller'));
        $this->assertEquals(1, $container->getParameter('restboot.transaction.priority.controller'));
        $this->assertEquals(1, $container->getParameter('restboot.transaction.priority.exception'));
        $this->assertEquals(1, $container->getParameter('restboot.transaction.priority.response'));
    }
}
