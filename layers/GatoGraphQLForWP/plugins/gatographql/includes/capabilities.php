<?php

declare(strict_types=1);

/**
 * Capabilities are defined via `define` instead of `const`,
 * because these are accessed before the vendor/ folder is
 * loaded, hence we can't reference the PSR4 file.
 *
 * Check if they are defined just in case 2 bundles, or a bundle
 * and an included extension, are loaded.
 */
if (!defined('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA')) {
    define('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA', 'gatographql_manage_graphql_schema');
}
