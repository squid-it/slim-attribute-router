<?php
declare(strict_types=1);

namespace Tests\Unit\SquidIT\Slim\Routing;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use SplFileObject;
use SquidIT\Slim\Routing\ClassMethod;
use SquidIT\Slim\Routing\AttributeReader;
use SquidIT\Slim\Routing\Exception\AttributeReaderException;
use TypeError;

class AttributeReaderTest extends TestCase
{
    public const TEST_DIR = __DIR__.DIRECTORY_SEPARATOR.'TestFiles';

    public const VALID_DIR = self::TEST_DIR.DIRECTORY_SEPARATOR.'Valid';

    public const INVALID_DIR = self::TEST_DIR.DIRECTORY_SEPARATOR.'Invalid';
    
    public const EXCLUDE_DIR = self::TEST_DIR.DIRECTORY_SEPARATOR.'Exclude';

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
            if (
                $fileInfo->isFile()
                && $fileInfo->getExtension() === 'php'
                && str_contains($fileInfo->getPathname(), self::EXCLUDE_DIR) === false
            ) {
                // include class files so that testing if the actual classes are present works
                include_once $fileInfo->getPathname();
            }
        }
    }

	public function testSingleLineValid(): void
	{
		$filename = self::VALID_DIR.'/SingleLineValid.php';
		$splFileObject = new SplFileObject($filename);

		$attributeRead = new AttributeReader($splFileObject);
		$classMethods = $attributeRead->findClassMethodInfo();
		self::assertIsArray($classMethods);
		self::assertCount(1, $classMethods);
		self::assertContainsOnlyInstancesOf(ClassMethod::class, $classMethods);
	}

    public function testSingleLineInvalidMethod(): void
    {
        $filename = self::INVALID_DIR.'/SingleLineInvalidMethod.php';
        $splFileObject = new SplFileObject($filename);

        $this->expectException(TypeError::class);
        $attributeRead = new AttributeReader($splFileObject);
        $attributeRead->findClassMethodInfo();
    }

    public function testSingleLineInvalidMethodEmpty(): void
    {
        $filename = self::INVALID_DIR.'/SingleLineInvalidMethodEmpty.php';
        $splFileObject = new SplFileObject($filename);

        $this->expectException(InvalidArgumentException::class);
        $attributeRead = new AttributeReader($splFileObject);
        $attributeRead->findClassMethodInfo();
    }

	public function testSingleLineValidInvoke(): void
	{
		$filename = self::VALID_DIR.'/SingleLineValidInvoke.php';
		$splFileObject = new SplFileObject($filename);

		$attributeRead = new AttributeReader($splFileObject);
		$classMethods = $attributeRead->findClassMethodInfo();
		self::assertCount(1, $classMethods);
		self::assertContainsOnlyInstancesOf(ClassMethod::class, $classMethods);
	}

    public function testSingleLineValidInvokeClassDoubleDefiniton(): void
    {
        $filename = self::VALID_DIR.'/SingleLineValidClassDoubleRouteDefiniton.php';
        $splFileObject = new SplFileObject($filename);

        $attributeRead = new AttributeReader($splFileObject);
        $classMethods = $attributeRead->findClassMethodInfo();
        self::assertCount(1, $classMethods);

        $classMethod = reset($classMethods);
        self::assertEquals('invokeMethod', $classMethod->getRouteName());
    }

    public function testMultiLineValid(): void
    {
        $filename = self::VALID_DIR.'/MultiLineValid.php';
        $splFileObject = new SplFileObject($filename);

        $attributeRead = new AttributeReader($splFileObject);
        $classMethods = $attributeRead->findClassMethodInfo();
        self::assertIsArray($classMethods);
        self::assertCount(1, $classMethods);
        self::assertContainsOnlyInstancesOf(ClassMethod::class, $classMethods);
    }

    public function testSingleLineValidExcludeMethod(): void
    {
        $filename = self::EXCLUDE_DIR.'/SingleLineValidExclude.php';
        $splFileObject = new SplFileObject($filename);

        $this->expectException(AttributeReaderException::class);
        $attributeRead = new AttributeReader($splFileObject);
        $attributeRead->findClassMethodInfo();
    }
}
