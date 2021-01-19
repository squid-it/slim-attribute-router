<?php
declare(strict_types=1);

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MultiLineValid
{
	/**
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	#[Route(
        '/test6/http/{statusCode:[1-5]{1}\d{2}}',
        ['GET', 'POST', 'PUT', 'DELETE'],
        'test6'
    )]    
	public function actionMethodName1(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}
}
