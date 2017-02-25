<?php

declare(strict_types=1);

namespace app\actions;

use app\components\storage\Storage;
use League\Glide\Server as GlideServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Download
 * @package app\actions
 */
class DownloadAction
{
    /**
     * @var GlideServer
     */
    private $server;

    /**
     * @var Storage
     */
    private $storage;

    public function __construct(GlideServer $glideServer, Storage $storage)
    {
        $this->server = $glideServer;
        $this->storage = $storage;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \app\components\storage\FileException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $path = $request->getUri()->getPath();
        $params = $request->getQueryParams();

        $file = $this->storage->getFileByName(ltrim($path, '/'));

        return $this->server->getImageResponse($file->getPath(), $params);
    }
}
