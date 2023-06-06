<?php

declare(strict_types=1);

namespace PoP\RootWP\Exception;

use PoP\Root\Exception\AbstractClientException;
use stdClass;
use Throwable;
use WP_Error;

/**
 * Abstract class to pass the error information
 * contained in class WP_Error
 */
abstract class AbstractWPErrorClientException extends AbstractClientException
{
    public int|string|null $errorCode;
    public ?stdClass $data;

    public function __construct(
        WP_Error $wpError = null,
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): int|string|null
    {
        return $this->errorCode;
    }

    public function getData(): ?stdClass
    {
        return $this->data;
    }
}
