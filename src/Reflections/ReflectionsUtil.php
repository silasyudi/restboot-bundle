<?php

namespace SilasYudi\RestBootBundle\Reflections;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use SilasYudi\RestBootBundle\Exception\NotTypedParameterException;
use SilasYudi\RestBootBundle\Exception\ParameterNotFoundException;

class ReflectionsUtil
{

    private const INVOKE_METHOD = '__invoke';
    private $class;
    private $method;

    public function __construct(callable $invoked)
    {
        $this->class = is_array($invoked) ? $invoked[0] : $invoked;
        $this->method = is_array($invoked) ? $invoked[1] : self::INVOKE_METHOD;
    }

    public function getMethod(): ReflectionMethod
    {
        return new ReflectionMethod($this->class, $this->method);
    }

    public function getClass(): ReflectionClass
    {
        return new ReflectionClass($this->class);
    }

    public function getMethodAnnotation(string $annotationClass)
    {
        $method = $this->getMethod();
        return (new AnnotationReader())->getMethodAnnotation($method, $annotationClass);
    }

    public function getClassAnnotation(string $annotationClass)
    {
        $class = $this->getClass();
        return (new AnnotationReader())->getClassAnnotation($class, $annotationClass);
    }

    public function getMethodOrClassAnnotation(string $annotationClass)
    {
        return $this->getMethodAnnotation($annotationClass)
            ?: $this->getClassAnnotation($annotationClass);
    }

    public function getTypeParameter(string $parameterName): string
    {
        $parameters = ($this->getMethod())->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->getName() == $parameterName) {
                return $this->extractTypeFromParameter($parameter);
            }
        }

        throw new ParameterNotFoundException("Parameter $parameterName not found in method signature.");
    }

    private function extractTypeFromParameter(ReflectionParameter $parameter): string
    {
        if (is_null($parameter->getType())) {
            throw new NotTypedParameterException("Parameter {$parameter->getName()} must be typed.");
        }

        return $parameter->getType()->getName();
    }
}
