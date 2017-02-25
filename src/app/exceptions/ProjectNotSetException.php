<?php

declare(strict_types=1);

namespace app\exceptions;

use RuntimeException;

/**
 * Class ProjectNotSetException
 * @package app\exceptions
 */
class ProjectNotSetException extends RuntimeException
{
    /**
     * ProjectNotSetException constructor.
     */
    public function __construct()
    {
        parent::__construct('Project not set');
    }
}
