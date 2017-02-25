<?php

declare(strict_types=1);

namespace app\exceptions;

use RuntimeException;

/**
 * Class ProjectNotExistsException
 * @package app\exceptions
 */
class ProjectNotExistsException extends RuntimeException
{
    /**
     * ProjectNotExistsException constructor.
     */
    public function __construct()
    {
        parent::__construct('Project not exists');
    }
}
