<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use app\exceptions\TokenNotSetException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class UploadAuthMiddleware
 * @package app\middleware
 */
class UploadAuthMiddleware
{
    /**
     * @var \app\components\project\Project
     */
    private $project;

    /**
     * UploadAuthMiddleware constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            $token = $this->getToken($request);

            if ($this->project->isValidUploadToken($token) === false) {
                return $response->withStatus(401, 'Wrong token');
            }
        } catch (TokenNotSetException $exception) {
            return $response->withStatus(400, $exception->getMessage());
        }

        return $next($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool|string
     * @throws \app\exceptions\ProjectNotSetException
     */
    private function getToken(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $token = $queryParams['token'] ?? null;

        if (!$token) {
            $token = $request->getHeaderLine('X-Token') ?? null;
        }

        return $token;
    }
}
