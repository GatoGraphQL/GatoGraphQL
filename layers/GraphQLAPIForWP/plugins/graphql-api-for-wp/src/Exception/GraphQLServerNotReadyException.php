<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Exception;

use PoP\Root\Exception\AbstractClientException;

/**
 * Indicate that the standalone GraphQLServer instance
 * has been requested before it can be initialized.
 */
final class GraphQLServerNotReadyException extends AbstractClientException
{
}
