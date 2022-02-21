<?php

namespace SymfonyBoot\SymfonyBootBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Loader
{

    private const SERVICES = [
        'symfonyboot.configurations.rest.enable' => 'rest.yml',
        'symfonyboot.configurations.transaction.enable' => 'transaction.yml',
    ];

    public static function load(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        foreach (self::SERVICES as $service => $yml) {
            if ($container->getParameter($service)) {
                $loader->load($yml);
            }
        }
    }
}
