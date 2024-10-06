<?php

declare(strict_types=1);

/**
 * Capabilities are defined via `define` instead of `const`,
 * because these are accessed before the vendor/ folder is
 * loaded, hence we can't reference the PSR4 file.
 */
define('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA', 'gatogql_manage_graphql_schema');
