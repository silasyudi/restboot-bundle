<?php

namespace SymfonyBoot\SymfonyBootBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SymfonyBoot\SymfonyBootBundle\Exception\ConverterNotFoundException;
use SymfonyBoot\SymfonyBootBundle\Reflections\ReflectionsUtil;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Rest;
use SymfonyBoot\SymfonyBootBundle\Rest\Converter\AbstractConverter;
use SymfonyBoot\SymfonyBootBundle\Rest\ValueObject\ConverterContext;
use Traversable;

class RestSubscriber implements EventSubscriberInterface
{

    /** @var AbstractConverter[] */
    private array $converters;

    public function __construct(Traversable $converters)
    {
        $this->converters = iterator_to_array($converters);
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $reflectionsUtil = new ReflectionsUtil($controller);
        /** @var Rest $annotation */
        $annotation = $reflectionsUtil->getMethodAnnotation(Rest::class);

        if ($annotation) {
            $context = $this->getContext($reflectionsUtil, $annotation);
            $converter = $this->getConverter($annotation->getConverter());
            $converter->apply($event->getRequest(), $context);
        }
    }

    private function getContext(ReflectionsUtil $reflectionsUtil, Rest $annotation): ConverterContext
    {
        $parameter = $annotation->getParameter();
        $type = $reflectionsUtil->getTypeParameter($parameter);
        return new ConverterContext($type, $parameter);
    }

    private function getConverter(string $class): AbstractConverter
    {
        foreach ($this->converters as $converter) {
            if (
                $converter instanceof AbstractConverter
                && get_class($converter) == $class
            ) {
                return $converter;
            }
        }

        throw new ConverterNotFoundException(
            "Converter '$class' doesn't exist or doesn't implement ConverterInterface."
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
