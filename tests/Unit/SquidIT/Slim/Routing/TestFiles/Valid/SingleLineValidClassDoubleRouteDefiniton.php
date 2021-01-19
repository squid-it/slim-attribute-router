<?php

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

#[Route('/test/httpClass2/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'invokeClass')]
class SingleLineValidClassDoubleRouteDefiniton
{
	/**
	 * SingleLineValidClass
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 *
	 * @return Response
	 */
    #[Route('/test/httpClass2/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'invokeMethod')]
	public function __invoke(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}
}
