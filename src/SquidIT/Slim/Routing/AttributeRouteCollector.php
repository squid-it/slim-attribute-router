<?php
/**
 * @author Cecil Zorg <developer@squidit.nl>
 * @copyright 2021 Squid IT
 */
declare(strict_types=1);

namespace SquidIT\Slim\Routing;

use BadFunctionCallException;
use ReflectionException;
use SplFileObject;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteCollector;
use SquidIT\Slim\Routing\Loader\FileLoader;

/**
 * Class AttributeRouteCollector
 * @package SquidIT\Slim\Routing
 */
class AttributeRouteCollector extends RouteCollector
{
    protected array $config = [];

    protected array $errors = [];

    private bool $forceReadingAttributes = false;

    /**
	 * AttributeRouteCollector constructor.
	 *
	 * @param array $config contains settings
	 * @param ResponseFactoryInterface $responseFactory
	 * @param CallableResolverInterface $callableResolver
	 * @param ContainerInterface|null $container
	 * @param InvocationStrategyInterface|null $defaultInvocationStrategy
	 * @param RouteParserInterface|null $routeParser
	 * @param string|null $cacheFile
	 */
	public function __construct(
		array $config,
		ResponseFactoryInterface $responseFactory,
		CallableResolverInterface $callableResolver,
		?ContainerInterface $container = null,
		?InvocationStrategyInterface $defaultInvocationStrategy = null,
		?RouteParserInterface $routeParser = null,
		?string $cacheFile = null
	) {
		parent::__construct(
			$responseFactory,
			$callableResolver,
			$container,
			$defaultInvocationStrategy,
			$routeParser,
			$cacheFile
		);

		if (!array_key_exists('path', $config) || (!is_array($config['path']) && $config['path'] !== null)) {
			throw new BadFunctionCallException('required config setting "path" is missing or not of type "array"');
		}
		// Store settings
		$this->config = $config;
	}

    public function getRoutes(): array
    {
        // check if caching file has been set, if not call
        if ($this->forceReadingAttributes || !isset($this->cacheFile) || !is_file($this->cacheFile)) {
            $this->addAttributeRoutes();
        }

        return parent::getRoutes();
    }

    public function setForceReadingAttributes(bool $bool): void
    {
        $this->forceReadingAttributes = $bool;
    }

	/**
	 * addAttributeRoutes
	 *
	 * Searches the filesystem path(s) recursively and adds all found attribute routes to our router
	 */
	protected function addAttributeRoutes(): int
	{
		$nrOfRoutesAdded = 0;

		// Check if a path containing Action/Controller methods is supplied
		if (empty($this->config['path'])) {
			return $nrOfRoutesAdded;
		}

		// Search for Action/Controller classes on our filesystem
        $fileLoader = new FileLoader($this->config['path']);
		if (empty($phpFiles = $fileLoader->getFiles())) {
			return $nrOfRoutesAdded;
		}

		// Get all class methods
		$classMethods = $this->findClassMethods($phpFiles);
		if (empty($classMethods)) {
		    return $nrOfRoutesAdded;
        }

        $routePatterns = [];
        foreach ($classMethods as $methodName => $classMethod) {
            $routePattern   = $classMethod->getRoutePattern();
            $requestMethods = $classMethod->getRouteMethods();

            // Check for duplicate entries
            if (array_key_exists($routePattern, $routePatterns)) {
                $nrOfCurrentRequestMethods = count($routePatterns[$routePattern]['requestMethods']);
                $diff = array_diff($routePatterns[$routePattern]['requestMethods'], $requestMethods);

                if ($nrOfCurrentRequestMethods !== count($diff)) {
                    $this->errors[] = [
                        'route' => $routePattern,
                        'classMethod' => $classMethod->getMethodName(),
                        'requestMethods' => $requestMethods,
                    ];

                    continue;
                }
            }

            // Add Route
            $route = $this->map(
                $requestMethods,
                $routePattern,
                $classMethod->getMethodName()
            );

            if ($name = $classMethod->getRouteName()) {
                $route->setName($name);
            }

            // Store added route in list to track duplicates
            foreach($requestMethods as $requestMethod) {
                $routePatterns[$routePattern]['requestMethods'][] = $requestMethod;
            }

            $nrOfRoutesAdded++;
        }

		return $nrOfRoutesAdded;
	}

    /**
     * @param SplFileObject[] $classFiles
     * @return ClassMethod[]
     * @throws Exception\AttributeReaderException
     * @throws ReflectionException
     */
	protected function findClassMethods(array $classFiles): array
    {
        $classMethodsList = [];

        foreach ($classFiles as $fileName => $splFileObject) {
            $attributeReader = new AttributeReader($splFileObject);
            $classMethods = $attributeReader->findClassMethodInfo();

            if (!empty($classMethods)) {
                $classMethodsList[] = $classMethods;
            }
        }

        // Removed array_merge outside of loop
        return array_merge(...$classMethodsList);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
