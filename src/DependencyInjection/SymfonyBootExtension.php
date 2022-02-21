<?php

namespace SymfonyBoot\SymfonyBootBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class SymfonyBootExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        Loader::load($container);
    }
}
