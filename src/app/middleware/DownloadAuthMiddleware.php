<?php

declare(strict_types=1);

namespace app\middleware;

use app\components\project\Project;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

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
     * @param LoggerInterface $logger
     */
    public function __construct(Project $project, LoggerInterface $logger)
    {
        $this->project = $project;
        $this->logger = $logger;
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
            $filePath = (string) $request->getUri()->getPath();
            $params = $request->getQueryParams();

            $this->logger->info("Download file: \"$filePath\"");
            $this->logger->info('With params: ' . serialize($params));

            unset($params['project'], $params['token']);

            SignatureFactory::create($this->project->getDownloadSignKey())->validateRequest(
                $filePath, $params
            );

        } catch (SignatureException $e) {
            $this->logger->warning('Wrong key for upload image');

            return $response->withStatus(401, 'Wrong token');
        }

        return $next($request, $response);
    }
}
