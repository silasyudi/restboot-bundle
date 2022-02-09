<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SymfonyBoot\SymfonyBootBundle\SymfonyBootBundle;

class SymfonyBootBundleTest extends TestCase
{
    public function testLoad()
    {
        $configs = [];
        $container = new ContainerBuilder();
        (new SymfonyBootBundle())->load($configs, $container);

        $this->assertEmpty($configs);
        $this->assertCount(9, $container->getServiceIds());
    }
}
