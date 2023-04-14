<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Exception;

use PoP\RootWP\AppHooks;
use PoP\Root\Exception\AbstractClientException;
use Throwable;

/**
 * Indicate that the standalone GraphQLServer instance
 * has been requested before it can be initialized.
 *
 * @see layers/Engine/packages/root-wp/src/AppLoader.php
 */
final class GraphQLServerNotReadyException extends AbstractClientException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        if (empty($message)) {
            $message = sprintf(
                \__('The GraphQL server is not ready yet. Its initialization takes place in WordPress action hooks: \'%s\' in the wp-admin, \'%s\' in the WP REST API, and \'%s\' otherwise (i.e. in the actual website). Retrieve the instance of the GraphQL server only after these hooks have been invoked.', 'graphql-api'),
                AppHooks::BOOT_APP_IN_ADMIN,
                AppHooks::BOOT_APP_IN_REST,
                AppHooks::BOOT_APP_IN_FRONTEND
            );
        }
        parent::__construct($message, $code, $previous);
    }
}
