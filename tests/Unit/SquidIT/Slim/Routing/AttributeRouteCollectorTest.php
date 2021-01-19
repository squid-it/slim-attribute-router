<?php
declare(strict_types=1);

namespace Tests\Unit\SquidIT\Slim\Routing;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Slim\CallableResolver;
use Slim\Factory\AppFactory;
use SplFileInfo;

use SquidIT\Slim\Routing\AttributeRouteCollector;

class AttributeRouteCollectorTest extends TestCase
{
    public const TEST_DIR = __DIR__.'/TestFiles/Valid';

    protected static int $nrOfRoutes = 0;

    public static function setUpBeforeClass(): void
    {
        // load valid files by recursively walking through valid directory
        $directoryIterator = new RecursiveDirectoryIterator(
            self::TEST_DIR,
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        /** @var SplFileInfo[] $filesystemIterator */
        $filesystemIterator = new RecursiveIteratorIterator(
            $directoryIterator,
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        foreach ($filesystemIterator as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getExtension() === 'php') {
                self::$nrOfRoutes++;

                // include class files so that testing if the actual classes are present works
                include_once $fileInfo->getPathname();
            }
        }
    }

	public function testAddAttributeRoutes(): void
	{
		$attributeRouteCollector = new AttributeRouteCollector([
			'path' => [self::TEST_DIR,]
			],
			AppFactory::determineResponseFactory(),
			new CallableResolver(),
            cacheFile: __DIR__.'/cache.php'
		);

		self::assertInstanceOf(AttributeRouteCollector::class, $attributeRouteCollector);
		$routes = $attributeRouteCollector->getRoutes();
		$routesNotAdded = $attributeRouteCollector->getErrors();

		self::assertCount(self::$nrOfRoutes, $routes);
		self::assertCount(1, $routesNotAdded);
	}
}
