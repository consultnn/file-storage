<?php

declare(strict_types=1);

namespace app\components\storage;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Exception;
use League\Flysystem\Filesystem;

/**
 * Class File
 * @package app\components\storage
 */
class File
{
    public $directoryNameLength = 2;

    public $pathDeep = 2;

    private $name;

    private $path;

    private $extension;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileNameMaker
     */
    private $fileNameMaker;

    /**
     * @var string
     */
    private $hash;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->fileNameMaker = new FileNameMaker();
    }

    /**
     * @param $name
     * @return $this
     * @throws FileException
     */
    public function load($name)
    {
        $this->name = $name;
        if (!$this->filesystem->has($this->getPath())) {
            unset($this->name);
            throw new FileException("File with name '{$name}' does not exists");
        }

        return $this;
    }

    public function getHash()
    {
        if ($point = strpos($this->name, '.')) {
            return substr($this->name, 0, $point);
        }

        return $this->name;
    }

    /**
     * @param string $fileName
     * @return $this
     * @throws FileException
     */
    public function upload($fileName)
    {
        if (dirname($fileName) !== sys_get_temp_dir()) {
            throw new FileException('bad file location');
        }

        if (!is_file($fileName)) {
            throw new FileException('file does not exists');
        }

        $newName = $this->fileNameMaker->makeName($fileName);
        $this->name = $newName;

        try {
            if (!$this->filesystem->has($this->getPath())) {
                $stream = fopen($fileName, 'rb+');
                $this->filesystem->writeStream($this->getPath(), $stream);
                fclose($stream);
            }
        } catch (Exception $e) {
            unset($this->name);
            throw new FileException($e->getMessage());
        }

        return $this;
    }

    public function getContent()
    {
        return $this->filesystem->read($this->getPath());
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if ($this->path) {
            return $this->path;
        }

        $name = $this->getHash();
        $directories = str_split(substr($name, 0, $this->directoryNameLength * $this->pathDeep), $this->pathDeep);

        return $this->path = implode(DIRECTORY_SEPARATOR, $directories) . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     * @throws FileException
     */
    public function getExtension()
    {
        if ($this->extension) {
            return $this->extension;
        }

        return $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
    }
}
