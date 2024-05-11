<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use PoP\ComponentModel\Constants\Params;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

/**
 * Use:
 *
 *   curl -i \
 *     --user "{ USER }:{ APPLICATION PASSWORD}" \
 *     -X POST \
 *     -H "Content-Type: application/json" \
 *     -d '{"query": "{ id me { name } }"}' \
 *     https://mysite.com/graphql/?actions[]=gql-auth-app-pwd
 */
class ApplicationPasswordAuthorizationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \add_filter(
            'application_password_is_api_request',
            $this->isAPIRequest(...),
            PHP_INT_MAX // Execute last
        );
    }

    public function isAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        $actions = App::request(Params::ACTIONS) ?? App::query(Params::ACTIONS) ?? [];
        return in_array('gql-auth-app-pwd', $actions);
    }
}
