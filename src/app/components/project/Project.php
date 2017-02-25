<?php

declare(strict_types=1);

namespace app\components\project;

/**
 * Class Project
 * @package app\components
 */
class Project {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $uploadToken;

    /**
     * @var string
     */
    private $downloadToken;

    /**
     * @param string $name
     * @param $uploadToken
     * @param $downloadToken
     */
    public function __construct(string $name, string $uploadToken, string $downloadToken)
    {
        $this->name = $name;
        $this->uploadToken = $uploadToken;
        $this->downloadToken = $downloadToken;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isValidUploadToken(string $token): bool
    {
        return $token === $this->uploadToken;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isValidDownloadToken(string $token): bool
    {
        return $token === $this->downloadToken;
    }

    /**
     * @return string
     */
    public function getDownloadToken(): string
    {
        return $this->downloadToken;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
