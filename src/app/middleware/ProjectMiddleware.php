<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use app\components\project\ProjectList;
use app\exceptions\ProjectNotExistsException;
use app\exceptions\ProjectNotSetException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @param Project $projectList
     */
    public function __construct(ProjectList $projectList)
    {
        $this->projectList = $projectList;
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
            return $response->withStatus(400, $e->getMessage());
        } catch (ProjectNotSetException $e) {
            return $response->withStatus(400, $e->getMessage());
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
            $projectName = $request->getHeader('X-Project');
        }

        return $projectName;
    }
}
