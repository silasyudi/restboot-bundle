<?php

namespace SymfonyBoot\SymfonyBootBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SymfonyBoot\SymfonyBootBundle\DependencyInjection\Loader;

class SymfonyBootBundle extends Bundle
{
    public function load(array $configs, ContainerBuilder $container)
    {
        Loader::load($container);
    }
}
