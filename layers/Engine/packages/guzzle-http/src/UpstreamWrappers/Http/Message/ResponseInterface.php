<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\UpstreamWrappers\Http\Message;

use Psr\Http\Message\ResponseInterface as UpstreamResponseInterface;

/**
 * Extend the interface so this one can be returned in the GuzzleService
 * methods, and it will not be scoped (the Psr upstream one will be scoped).
 *
 * ---------------------------------------------------------------------------
 *
 * Representation of an outgoing, server-side response.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - Status code and reason phrase
 * - Headers
 * - Message body
 *
 * Responses are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
interface ResponseInterface extends UpstreamResponseInterface
{
}
