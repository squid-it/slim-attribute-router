<?php
/**
 * @author Cecil Zorg <developer@squidit.nl>
 * @copyright 2021 Squid IT
 */
declare(strict_types=1);

namespace SquidIT\Slim\Routing;

use SquidIT\Slim\Routing\Attributes\Route;

/**
 * Class ClassMethod
 * @package SquidIT\Slim\Routing
 */
class ClassMethod
{
    protected string $className;

    protected string $methodName;

    protected Route $route;

    /**
     * ClassMethod constructor.
     *
     * @param string $className
     * @param string $methodName
     * @param Route $route
     */
    public function __construct(string $className, string $methodName, Route $route)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        $methodName = ($this->methodName === 'class') ? '__invoke' : $this->methodName;
        return $this->className.':'.$methodName;
    }

    /**
     * @return string
     */
    public function getRoutePattern(): string
    {
        return $this->route->getPattern();
    }

    /**
     * @return array
     */
    public function getRouteMethods(): array
    {
        return $this->route->getMethods();
    }

    /**
     * @return string|null
     */
    public function getRouteName(): ?string
    {
        return $this->route->getName();
    }
}
