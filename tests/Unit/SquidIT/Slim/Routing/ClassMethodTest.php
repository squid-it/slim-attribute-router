<?php
declare(strict_types=1);

namespace Tests\Unit\SquidIT\Slim\Routing;

use PHPUnit\Framework\TestCase;
use SquidIT\Slim\Routing\Attributes\Route;
use SquidIT\Slim\Routing\ClassMethod;

class ClassMethodTest extends TestCase
{
    // getMethodName
    public function testGetMethodNameRegularMethod(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'test');
        $classMethod = new ClassMethod('ClassMethod', 'getMethodName', $route);
        $this->assertEquals('ClassMethod:getMethodName', $classMethod->getMethodName());
    }

    public function testGetMethodNameInvokeMethod(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'test');
        $classMethod = new ClassMethod('ClassMethod', 'class', $route);
        $this->assertEquals('ClassMethod:__invoke', $classMethod->getMethodName());
    }

    // getRoute
    public function testGetRouteSuccess(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'test');
        $classMethod = new ClassMethod('ClassName', 'methodName', $route);
        $this->assertEquals('/test1/http/{statusCode:[1-5]{1}\d{2}}', $classMethod->getRoutePattern());
    }

    // getRouteMethods
    public function testGetRouteMethodsSuccess(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST'], 'test');
        $classMethod = new ClassMethod('ClassName', 'methodName', $route);
        $methods = $classMethod->getRouteMethods();

        self::assertContains('GET', $methods);
        self::assertContains('POST', $methods);
        self::assertNotContains('DELETE', $methods);
    }

    // getRoutName
    public function testGetRouteNameSuccess(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST'], 'test_http_statuscode');
        $classMethod = new ClassMethod('ClassName', 'methodName', $route);
        $this->assertEquals('test_http_statuscode', $classMethod->getRouteName());
    }

    public function testGetRouteNameFail(): void
    {
        $route = new Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST']);
        $classMethod = new ClassMethod('ClassName', 'methodName', $route);
        $this->assertEquals(null, $classMethod->getRouteName());
    }
}
