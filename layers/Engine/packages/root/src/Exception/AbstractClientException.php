<?php

declare(strict_types=1);

namespace PoP\Root\Exception;

/**
 * Exceptions that are safe to be shown to the user
 * of the application, such as GraphQL query validations.
 */
abstract class AbstractClientException extends AbstractException
{
}
