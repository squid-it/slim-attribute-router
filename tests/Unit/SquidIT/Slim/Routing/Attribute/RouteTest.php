<?php
declare(strict_types=1);

namespace Tests\Unit\SquidIT\Slim\Routing\Attribute;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SquidIT\Slim\Routing\Attributes\Route;

class RouteTest extends TestCase
{
    public function testSetPattern(): void
    {
        $routePattern = '/test1/path/{statusCode:[1-5]{1}\d{2}}';
        $route = new Route($routePattern, ['GET'], 'test');
        self::assertEquals($routePattern, $route->getPattern());
    }

    public function testSetPatternThrowsInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $route = new Route('test1/path/', ['GET'], 'test');
    }

    public function testSetMethods(): void
    {
        $methods = ['GET', 'POST'];
        $route = new Route('/test1/path/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST'], 'test');
        self::assertEqualsCanonicalizing($methods, $route->getMethods());
    }

    public function testSetMethodsInvalidMethodName(): void
    {
        $this->expectExceptionMessage('invalid method name supplied');
        $route = new Route('/test1/path/{statusCode:[1-5]{1}\d{2}}', ['GEL', 'DELETE'], 'test');
    }

    public function testSetMethodsEmptyString(): void
    {
        $this->expectExceptionMessage('invalid method name supplied');
        $route = new Route('/test1/path/{statusCode:[1-5]{1}\d{2}}', [''], 'test');
    }

    public function testSetMethodsEmpty(): void
    {
        $this->expectExceptionMessage('can not be empty');
        $route = new Route('/test1/path/{statusCode:[1-5]{1}\d{2}}', [], 'test');
    }
}
