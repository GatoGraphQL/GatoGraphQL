<?php

declare(strict_types=1);

namespace PoP\Root\Exception;

/**
 * Exceptions that may contain sensitive information,
 * so they should be accessed by the admin only.
 */
abstract class AbstractSystemException extends AbstractException
{
}
