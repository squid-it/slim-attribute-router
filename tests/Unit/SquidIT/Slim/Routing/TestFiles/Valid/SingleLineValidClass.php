<?php

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

#[Route('/test/httpClass1/{statusCode:[1-5]{1}\d{2}}', ['GET'], 'invoke1')]
class SingleLineValidClass
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
	public function __invoke(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}
}
