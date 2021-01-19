# Slim (v4) Framework - Squid IT Attribute Router
Attribute Action/Controller router

This package allows you to add routes to your slim4 (https://www.slimframework.com) application using attributes in your action classes.

### Features
* Route Method support
* Route name support

## Attribute signature
**#[Route({route}[[, {methods}], {routeName}])]**

| Parameter   | example value           | Description                               |
|-------------|-------------------------|-------------------------------------------|
| {route}     | '/hello/{name}'         | (string) The route pattern                |
| {methods}   | ['GET', 'POST']         | (array)  The allowed HTTP request methods |
| {routeName} | 'helloRoute'            | (string) The name of the route            |

## Adding Attribute to an ActionController
If you want to add a route using attributes you can accomplish this by adding a `#[Route({route}[[, {methods}], {routeName}])]`
route tag to your class method. please see examples:  

Example  
web address test 1: https://server.name/test1/http/202
```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class StatusCodeAction
{
    #[Route('/test1/http/{statusCode:[1-5]{1}\d{2}}',['GET', 'POST', 'PUT', 'DELETE'], 'test_http_statuscode')]
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // action/controller code
        return $response;
    }
}
```
Examples without a 'name' parameter  
web address test 2: https://server.name/test2/http/202  
web address test 3: https://server.name/test3/http/202
```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StatusCodeAction
{
    #[Route('/test2/http/{statusCode:[1-5]{1}\d{2}}',['POST'])]
    public function testMethod2(Request $request, Response $response, array $args): Response
    {
        // action/controller code
        return $response;
    }
	
    #[Route('/test3/http/{statusCode:[1-5]{1}\d{2}}',['DELETE'])]
    public function testMethod3(Request $request, Response $response, array $args): Response
    {
        // action/controller code
        return $response;
    }
}
```

* The methods parameter is required when writing the Route attribute
* The path to your ActionController files needs to be specified when instantiating the attribute router


## Installation
Place the contents of the src folder in you local copy of the source folder
```bash
cp -r /extracted/src/. /app/src
```

Make sure that composer autoload can find our files by adding the following entry into the autoload section of the
composer.json file (if not already present)

```json
{
  "autoload": {
    "psr-4": {
      "SquidIT\\Slim\\Routing": "src/SquidIT/Slim/Routing"
    }
  }
}

```
## Enabling the Attribute Router
Our attribute router extends slims default RouteCollector, so we can just instantiate our attribute router and pass
it on to our AppFactory

```php
<?php
use SquidIT\Slim\Routing\AttributeRouteCollector;
use Slim\Factory\AppFactory;

// create AttributeRouteCollector
$attributeRouteCollector = new AttributeRouteCollector([
    'path' => [
        '/path/to/ActionControllers',
        '/different/path/to/ActionControllers/if/needed',
    ]],
	AppFactory::determineResponseFactory(),
	new CallableResolver($container) // pass in your DI container if you are using a container
);

// Instantiate the app
AppFactory::setRouteCollector($attributeRouteCollector);
$app = AppFactory::create();
$app->run();
```
