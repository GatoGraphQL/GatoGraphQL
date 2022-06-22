<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;

class AbstractCustomPostRESTEntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
{
    protected function doGetGraphQLQueryToResolveRESTEndpoint(): string
    {
        return <<<GRAPHQL
            query {
                id
                title
                date
                url
                content
            }
        GRAPHQL;
    }

    /**
     * Add an additional hook on this abstract class
     */
    public function getGraphQLQueryToResolveRESTEndpoint(): string
    {
        if ($this->restEndpointGraphQLQuery === null) {
            $this->restEndpointGraphQLQuery = (string) App::applyFilters(
                HookHelpers::getHookName(__CLASS__),
                parent::getGraphQLQueryToResolveRESTEndpoint()
            );
        }
        return $this->restEndpointGraphQLQuery;
    }
}
