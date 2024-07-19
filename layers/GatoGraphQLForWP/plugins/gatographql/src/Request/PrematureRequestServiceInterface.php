<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Request;

/**
 * Use:
 *
 *   curl -i \
 *     --user "{ USER }:{ APPLICATION PASSWORD}" \
 *     -X POST \
 *     -H "Content-Type: application/json" \
 *     -d '{"query": "{ id me { name } }"}' \
 *     https://mysite.com/graphql/
 */
interface PrematureRequestServiceInterface
{
    /**
     * Check if requesting a GraphQL endpoint.
     *
     * Because the AppStateProviders have not been initialized yet,
     * we can't check ->doingJSON().
     *
     * As a workaround, retrieve the configuration for all GraphQL endpoints
     * (Single endpoint, custom endpoint, and persisted queries) and,
     * if any of them is enabled, check if the URL starts with their
     * path (even if that specific endpoint is disabled).
     *
     * Notice this checks only for the publicly-exposed GraphQL
     * endpoints (i.e. not for `wp-admin/edit.php?page=gatographql&action=execute_query`
     * or any of those).
     */
    public function isPubliclyExposedGraphQLAPIRequest(): bool;
}
