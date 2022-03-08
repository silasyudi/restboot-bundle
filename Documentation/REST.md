# Rest Converter Annotations

With the annotations @Body and @Query you automatically convert payloads or queries strings,
respectively, on objects in controller methods.

## Summary

- [How it works?](#how-it-works)
- Usage
    - [Annotation @Body](#annotation-body)
    - [Annotation @Query](#annotation-query)
    - [DTO Format (Data Transfer Object)](#dto-format-data-transfer-object)
- [RestListener Priority](#restlistener-priority)

## How it works?

The SymfonyBoot\SymfonyBootBundle\EventListener\RestListener listener checks for the presence of annotations
@Body or @Query on controller methods on triggered routes.

If so, the content is converted to an object through Symfony's [SerializerInterface](https://symfony.com/doc/current/components/serializer.html).
If this annotation is @Body, the content of the request's *body* is used for the conversion. If the annotation is @Query,
the *query string* of the request URL is used for conversion.

## Usage

### Annotation @Body

#### Requirements

To convert a payload from a POST, PUT, PATCH or DELETE request, it must be sent in the body of the request, 
in [supported format](https://symfony.com/doc/current/serializer.html#adding-normalizers-and-encoders) by Symfony's
SerializerInterface (such as JSON or XML).

#### Content-type

It is important that the appropriate content-type is sent in the request header.
In the absence of this information, the application will use JSON format as default.

With this setting in your *services.yml* you can change the default content-type:

```yaml
parameters:
  symfonyboot.rest.payload.format: 'xml' #changing to XML
```

Note that the format name is used here instead media-type.

#### Using annotation in the controller

The @Body annotation must be used in the method signature where a route is started:

```php
use Symfony\Component\Routing\Annotation\Route;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Body;

/**
 * @Route("/action", methods={"POST"})
 * @Body(parameter="myObject") 
 */
public function __invoke(MyObjectDTO $myObject) 
{ 
    ...
```

**Important:**
- You MUST enter the name of the variable of the object that will receive the payload as an argument in the @Body annotation;
- The method signature MUST contain the object to which the payload will be converted and this object MUST be typed;
- Each method only supports ONE Rest annotation. If you use more than one, the application will only consider the first one valid.

Note: when passing the argument in the @Body annotation, both forms below are valid:

```php
/**
 * @Body(parameter="myObject") 
 */

/**
 * @Body("myObject") 
 */
```

### Annotation @Query

The @Query annotation must be used in the method signature where a route is started:

```php
use Symfony\Component\Routing\Annotation\Route;
use SymfonyBoot\SymfonyBootBundle\Rest\Annotation\Query;

/**
 * @Route("/action", methods={"GET"})
 * @Query(parameter="myObject") 
 */
public function __invoke(MyObjectDTO $myObject) 
{ 
    ...
```

**Important:**
- You MUST enter the name of the variable of the object that will receive the query string as an argument in the @Query annotation;
- The method signature MUST contain the object to which the query string will be converted and this object MUST be typed;
- Each method only supports ONE Rest annotation. If you use more than one, the application will only consider the first one valid.

Note: when passing the argument in the @Query annotation, both forms below are valid:

```php
/**
 * @Query(parameter="myObject") 
 */

/**
 * @Query("myObject") 
 */
```

### DTO Format (Data Transfer Object)

If you have not made any specific SerializerInterface settings in your project, the Kernel will load the default SerializerInterface. 
This means that your object MUST contain properties with the same names as the payload, and MUST contain the corresponding getters and setters.

If you made specific settings for the SerializerInterface, your object must be in a format that allows it to be deserialized using these settings.

**Some important information for the SerializerInterface default configuration:**
- Typed properties will be cast.
- *int* types MUST be represented as integer or numeric string.
- *float* types MUST be represented as decimal or decimal string. *Float* types MUST have decimal places separated by dots.
- *boolean* types MUST:
    - have the value *true* represented as: boolean *true*, integer or decimal not equal to 0, or string other than empty string or string "0".
    - have the value *false* represented as: boolean *false*, integer or decimal equals 0, or string equals empty string or string "0".
- Dates MUST be represented in RFC3339 format (Y-m-d\TH:i:sP)

For more information, see the  [Serializer Component](https://symfony.com/doc/current/components/serializer.html) documentation.

## RestListener Priority

The RestListener's priority in the call chain is set to **1** to event *kernel.controller*.
To change this value, in the Symfony settings specify as follows:

```yaml
parameters:
  symfonyboot.rest.priority.controller: 0 #changing to 0
```

Obs.: Symfony allows you to specify any positive, negative, or zero integer values.
