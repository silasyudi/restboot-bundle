<?php

namespace Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;
use SymfonyBoot\SymfonyBootBundle\SymfonyBootBundle;

class AppKernel extends Kernel
{

    public function registerBundles()
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
                if (!$container->hasDefinition('kernel')) {
                    $container->register('kernel', static::class)
                        ->setSynthetic(true)
                        ->setPublic(true);
                }

                $kernelDefinition = $container->getDefinition('kernel');
                $kernelDefinition->addTag('routing.route_loader');

                if ($this instanceof EventSubscriberInterface) {
                    $kernelDefinition->addTag('kernel.event_subscriber');
                }

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
