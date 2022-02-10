<?php

namespace SymfonyBoot\SymfonyBootBundle\EventSubscriber;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Rest;
use SymfonyBoot\SymfonyBootBundle\Rest\Converter\AbstractConverter;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\ConverterNotFoundException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\MoreThanOneRestParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\NotTypedParameterException;
use SymfonyBoot\SymfonyBootBundle\Rest\Exception\ParameterNotFoundException;
use SymfonyBoot\SymfonyBootBundle\Rest\ValueObject\ConverterContext;
use Traversable;

class RestSubscriber implements EventSubscriberInterface
{

    private const INVOKE_METHOD = '__invoke';
    /** @var AbstractConverter[] */
    private array $converters;

    public function __construct(Traversable $converters)
    {
        $this->converters = iterator_to_array($converters);
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $method = $this->getMethodController($controller);
        $annotation = $this->getAnnotation($method);

        if (!$annotation) {
            return;
        }

        $parameter = $annotation->getParameter();
        $type = $this->getTypeParameter($method, $parameter);
        $context = new ConverterContext($type, $parameter);

        $converter = $this->getConverter($annotation->getConverter());
        $converter->apply($event->getRequest(), $context);
    }

    private function getMethodController(callable $controller): ReflectionMethod
    {
        $class = is_array($controller) ? $controller[0] : $controller;
        $method = is_array($controller) ? $controller[1] : self::INVOKE_METHOD;

        return new ReflectionMethod($class, $method);
    }

    private function getAnnotation(ReflectionMethod $method): ?Rest
    {
        $annotations = (new AnnotationReader())->getMethodAnnotations($method);

        $restAnnotations = array_filter(
            $annotations,
            function ($annotation) {
                return $annotation instanceof Rest;
            }
        );

        if (count($restAnnotations) > 1) {
            throw new MoreThanOneRestParameterException('The method has more than one type of rest parameter.');
        }

        return reset($restAnnotations) ?: null;
    }

    private function getTypeParameter(ReflectionMethod $method, string $name): string
    {
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->getName() == $name) {
                return $this->extractTypeFromParameter($parameter);
            }
        }

        throw new ParameterNotFoundException("Parameter $name not found in method signature.");
    }

    private function extractTypeFromParameter(ReflectionParameter $parameter): string
    {
        if (is_null($parameter->getType())) {
            throw new NotTypedParameterException("Parameter {$parameter->getName()} must be typed.");
        }

        return $parameter->getType()->getName();
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
