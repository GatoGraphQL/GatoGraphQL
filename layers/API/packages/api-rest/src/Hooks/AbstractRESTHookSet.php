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
        return str_replace('query {', 'query { ' . $this->getGraphQLFieldsToAppend() . ' ', $restEndpointGraphQLQuery);
    }

    abstract protected function getGraphQLFieldsToAppend(): string;
}
