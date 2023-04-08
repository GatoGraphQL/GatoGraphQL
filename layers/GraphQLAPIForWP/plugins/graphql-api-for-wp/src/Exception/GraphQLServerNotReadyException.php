<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Exception;

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
                'wp_loaded',
                'rest_api_init',
                'wp'
            );
        }
        parent::__construct($message, $code, $previous);
    }
}
