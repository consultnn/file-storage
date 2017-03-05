<?php

declare(strict_types=1);

namespace app\components\storage;

use League\Flysystem\Filesystem;

/**
 * Class Filesystem
 */
class Storage
{
    /** @var  Filesystem */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $uploadFilePath
     * @return File
     * @throws FileException
     */
    public function save($uploadFilePath): File
    {
        return (new File($this->filesystem))->upload($uploadFilePath);
    }

    /**
     * @param $fileName
     * @return File
     * @throws \app\components\storage\FileException
     */
    public function getFileByName($fileName): File
    {
        return (new File($this->filesystem))->load($fileName);
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }
}
