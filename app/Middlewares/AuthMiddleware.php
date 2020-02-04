<?php


namespace App\Middlewares;


use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{

    private $routesProtected = [
        '/admin', '/user/create', '/add/job'
    ];
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $urlRequested = $request->getUri()->getPath();
        if (in_array($urlRequested, $this->routesProtected)) {
            $userSession = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
            if (!$userSession) {
                return new RedirectResponse('/auth');
            }
        }
        return $handler->handle($request);
    }
}