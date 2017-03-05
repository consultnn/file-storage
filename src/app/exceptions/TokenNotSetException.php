<?php

declare(strict_types=1);

namespace app\exceptions;

use RuntimeException;

/**
 * Class TokenNotSetException
 * @package app\exceptions
 */
class TokenNotSetException extends RuntimeException
{
    /**
     * TokenNotSetException constructor.
     */
    public function __construct()
    {
        parent::__construct('Token not set');
    }
}
