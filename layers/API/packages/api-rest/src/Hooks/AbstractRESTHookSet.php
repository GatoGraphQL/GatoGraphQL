<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractRESTHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            $this->getHookName(),
            $this->hookGraphQLQueryToResolveRESTEndpoint(...)
        );
    }

    abstract protected function getHookName(): string;

    public function hookGraphQLQueryToResolveRESTEndpoint($restEndpointGraphQLQuery): string
    {
        // Find the position of the last `}` and append the new fields before it
        $pos = strrpos($restEndpointGraphQLQuery, '}');
        if ($pos === false) {
            // GraphQL query has error!?
            return $restEndpointGraphQLQuery;
        }
        return substr($restEndpointGraphQLQuery, 0, $pos) . ' ' . $this->getGraphQLFieldsToAppend() . ' ' . substr($restEndpointGraphQLQuery, $pos);
    }

    abstract protected function getGraphQLFieldsToAppend(): string;
}
