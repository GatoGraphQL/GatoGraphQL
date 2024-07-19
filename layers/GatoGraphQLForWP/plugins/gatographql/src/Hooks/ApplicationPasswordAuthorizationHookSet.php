<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Hooks;

use GatoGraphQL\GatoGraphQL\Request\PrematureRequestServiceInterface;
use PoP\Root\Hooks\AbstractHookSet;

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
class ApplicationPasswordAuthorizationHookSet extends AbstractHookSet
{
    private ?PrematureRequestServiceInterface $prematureRequestService = null;

    final public function setPrematureRequestService(PrematureRequestServiceInterface $prematureRequestService): void
    {
        $this->prematureRequestService = $prematureRequestService;
    }
    final protected function getPrematureRequestService(): PrematureRequestServiceInterface
    {
        if ($this->prematureRequestService === null) {
            /** @var PrematureRequestServiceInterface */
            $prematureRequestService = $this->instanceManager->getInstance(PrematureRequestServiceInterface::class);
            $this->prematureRequestService = $prematureRequestService;
        }
        return $this->prematureRequestService;
    }

    protected function init(): void
    {
        \add_filter(
            'application_password_is_api_request',
            $this->isGraphQLAPIRequest(...),
            PHP_INT_MAX // Execute last
        );
    }

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
    public function isGraphQLAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        return $this->getPrematureRequestService()->isPubliclyExposedGraphQLAPIRequest();
    }
}
