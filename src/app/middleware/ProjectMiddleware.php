<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\ProjectList;
use app\exceptions\ProjectNotExistsException;
use app\exceptions\ProjectNotSetException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Active current project
 *
 * Class ProjectMiddleware
 * @package app\middleware
 */
class ProjectMiddleware
{
    /**
     * @var ProjectList
     */
    private $projectList;

    /**
     * ProjectMiddleware constructor.
     * @param ProjectList $projectList
     * @param LoggerInterface $logger
     */
    public function __construct(ProjectList $projectList, LoggerInterface $logger)
    {
        $this->projectList = $projectList;
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     * @throws \app\exceptions\ProjectNotExistsException
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $this->logger->info('Check project name');

        try {
            if (!$projectName = $this->getProjectName($request)) {
                throw new ProjectNotSetException();
            }

            $projectInstance = $this->projectList->get($projectName);

            if (!$projectInstance) {
                throw new ProjectNotExistsException();
            }

            $this->projectList->setActiveProject($projectInstance);

        } catch (ProjectNotExistsException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);

            return $response->withStatus(500, $e->getMessage());
        } catch (ProjectNotSetException $e) {
            $this->logger->warning($e->getMessage(), ['exception' => $e]);

            return $response->withStatus(500, $e->getMessage());
        }

        return $next($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool|string
     * @throws \app\exceptions\ProjectNotSetException
     */
    private function getProjectName(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $projectName = $queryParams['project'] ?? null;

        if (!$projectName) {
            $projectName = $request->getHeaderLine('X-Project') ?? null;
        }

        $this->logger->info("Project name is \"$projectName\"");

        return $projectName;
    }
}
