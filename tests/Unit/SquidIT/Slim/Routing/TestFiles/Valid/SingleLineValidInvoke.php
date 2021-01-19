<?php
declare(strict_types=1);

use SquidIT\Slim\Routing\Attributes\Route;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SingleLineValidInvoke
{
	/**
	 * SingleLineValidInvoke constructor.
	 */
	public function __construct()
	{
		// present for testing purpose
	}

	/**
	 * __invoke
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param array $args
	 * @return Response
     */
    #[Route('/test1/http/{statusCode:[1-5]{1}\d{2}}', ['GET', 'POST', 'PUT', 'DELETE'], 'test1')]
	public function __invoke(Request $request, Response $response, array $args): Response
	{
		// action/controller code
		return $response;
	}

}
