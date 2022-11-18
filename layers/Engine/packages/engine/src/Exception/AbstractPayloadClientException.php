<?php

declare(strict_types=1);

namespace PoP\Engine\Exception;

use PoP\Root\Exception\AbstractClientException;
use Throwable;

/**
 * Retrieve extra information when passing the exception.
 *
 * Useful for passing extra data to a MutationPayload type,
 * instead of printing the error under `errors`
 */
abstract class AbstractPayloadClientException extends AbstractClientException
{
    public function __construct(
        string $message,
        public int|string|null $errorCode = null,
        public array $data = [],
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
