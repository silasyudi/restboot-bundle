<?php

namespace Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SymfonyBoot\SymfonyBootBundle\DependencyInjection\SymfonyBootExtension;

class SymfonyBootExtensionTest extends TestCase
{

    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        (new SymfonyBootExtension())->load([], $container);
        $this->assertEquals('json', $container->getParameter('symfonyboot.rest.payload.format'));
        $this->assertEquals(1, $container->getParameter('symfonyboot.rest.priority.controller'));
        $this->assertEquals(1, $container->getParameter('symfonyboot.transaction.priority.controller'));
        $this->assertEquals(1, $container->getParameter('symfonyboot.transaction.priority.exception'));
        $this->assertEquals(1, $container->getParameter('symfonyboot.transaction.priority.response'));
    }
}
