<?php

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SingleLineValid
{
	/**
	 * actionMethodName1
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
	#[Route('/test2/http/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST', 'PUT', 'DELETE'], 'test2')]
	public function actionMethodName1(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}
}
