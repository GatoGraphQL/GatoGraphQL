<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Exception;

use PoP\Root\Exception\AbstractClientException;
use stdClass;
use Throwable;

/**
 * Retrieve extra information when passing the exception.
 *
 * Useful for passing extra data to a ObjectMutationPayload type,
 * instead of printing the error under `errors`
 */
abstract class AbstractPayloadClientException extends AbstractClientException
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
