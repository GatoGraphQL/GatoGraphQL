<?php

declare(strict_types=1);

namespace PoP\RootWP;

use PoP\Root\Exception\AbstractClientException;
use stdClass;
use Throwable;

/**
 * Abstract class to pass the error information
 * contained in class WP_Error
 */
abstract class AbstractWPErrorClientException extends AbstractClientException
{
    public function __construct(
        string $message,
        public int|string|null $errorCode = null,
        public ?stdClass $data = null,
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
