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
        $this->assertCount(9, $container->getServiceIds());
    }
}
