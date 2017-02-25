<?php

declare(strict_types=1);

namespace app\components\storage;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Class Filesystem
 */
class Storage
{
    /** @var  \League\Flysystem\Filesystem */
    private $filesystem;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $path = rtrim($config['directory'], '/') . DIRECTORY_SEPARATOR . ltrim($config['prefix'], '/');
        if (!is_dir($path)) {
            mkdir($path);
        }
        $adapter = new Local($path);
        $this->filesystem = new Filesystem($adapter);
    }

    /**
     * @param string $uploadFilePath
     * @return File
     * @throws FileException
     */
    public function save($uploadFilePath)
    {
        return (new File($this->filesystem))->upload($uploadFilePath);
    }

    /**
     * @param $fileName
     * @return File
     * @throws \app\components\storage\FileException
     */
    public function getFileByName($fileName)
    {
        return (new File($this->filesystem))->load($fileName);
    }
}
