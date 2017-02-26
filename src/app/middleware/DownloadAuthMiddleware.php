<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        try {
            $params = $request->getQueryParams();

            unset($params['project'], $params['token']);

            SignatureFactory::create($this->project->getDownloadSignKey())->validateRequest(
                (string) $request->getUri()->getPath(), $params
            );

        } catch (SignatureException $e) {
            return $response->withStatus(401, 'Wrong token');
        }

        return $next($request, $response);
    }
}
