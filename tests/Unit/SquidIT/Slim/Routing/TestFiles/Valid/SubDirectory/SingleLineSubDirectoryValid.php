<?php
declare(strict_types=1);

namespace Tests\Unit\SquidIT\Slim\Routing\TestFiles\Valid\SubDirectory;

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SingleLineSubDirectoryValid
{
	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
    #[Route('/sub/test2/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST', 'PUT', 'DELETE'], 'sub_test1')]
	public function actionMethodName1(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}

	/**
	 * Duplicate route
     *
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
    #[Route('/sub/test2/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST', 'PUT', 'DELETE'], 'sub_test2')]
	public function actionMethodName2(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}
}
