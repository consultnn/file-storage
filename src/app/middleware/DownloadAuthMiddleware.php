<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use app\components\storage\FileName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class DownloadAuthMiddleware
 * @package app\middleware
 */
class DownloadAuthMiddleware
{
    /**
     * @var \app\components\project\Project
     */
    private $project;

    /**
     * DownloadAuthMiddleware constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        list($hash, $fileName, $params) = $this->getParams($request);

        if (!$this->authenticate($hash, $fileName, $params)) {
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }

    private function authenticate(string $hash, string $fileName, $params = '')
    {
        $downloadToken = $this->project->getDownloadToken();

        $newHash = FileName::internalHash($fileName, $params, $downloadToken);

        return $newHash === $hash;
    }

    private function getParams(ServerRequestInterface $request)
    {
        /**
         * @var \Slim\Route $route
         */
        $route = $request->getAttribute('route');

        $hash = $route->getArgument('hash');
        $file = $route->getArgument('file');
        $params = $route->getArgument('params', '');
        $extension = $route->getArgument('extension', '');

        $fileName = $file . '.' . $extension;

        return [$hash, $fileName, $params];
    }
}
