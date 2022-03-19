<?php

namespace Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use SymfonyBoot\SymfonyBootBundle\SymfonyBootBundle;

class AppKernel extends Kernel
{

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new SymfonyBootBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(
            function (ContainerBuilder $container) use ($loader) {
                $this->configureContainer($container, $loader);
                $container->addObjectResource($this);
            }
        );
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir() . '/tests/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);

        $loader->load($this->getProjectDir() . '/tests/Resources/config/doctrine.yml');
    }
}
