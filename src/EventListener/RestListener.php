<?php

namespace SilasYudi\RestBootBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use SilasYudi\RestBootBundle\Exception\ConverterNotFoundException;
use SilasYudi\RestBootBundle\Reflections\ReflectionsUtil;
use SilasYudi\RestBootBundle\Rest\Annotation\Rest;
use SilasYudi\RestBootBundle\Rest\Converter\AbstractConverter;
use SilasYudi\RestBootBundle\Rest\ValueObject\ConverterContext;
use Traversable;

class RestListener
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
}
