<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use app\exceptions\TokenNotSetException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $logger
     */
    public function __construct(Project $project, LoggerInterface $logger)
    {
        $this->project = $project;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->logger->info('Check upload token');

        try {
            $token = $this->getToken($request);

            if ($this->project->isValidUploadToken($token) === false) {
                $this->logger->info('Upload token not valid');

                return $response->withStatus(401, 'Wrong token');
            }
        } catch (TokenNotSetException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);

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

        $this->logger->info("Upload token is \"$token\"");

        return $token;
    }
}
