<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoPAPI\RESTAPI\Helpers\HookHelpers;

abstract class AbstractRESTEntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    protected ?string $restEndpointGraphQLQuery = null;

    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;

    final protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        if ($this->restDataStructureFormatter === null) {
            /** @var RESTDataStructureFormatter */
            $restDataStructureFormatter = $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
            $this->restDataStructureFormatter = $restDataStructureFormatter;
        }
        return $this->restDataStructureFormatter;
    }

    public function getGraphQLQueryToResolveRESTEndpoint(): string
    {
        if ($this->restEndpointGraphQLQuery === null) {
            $this->restEndpointGraphQLQuery = (string) App::applyFilters(
                HookHelpers::getHookName(get_called_class()),
                $this->doGetGraphQLQueryToResolveRESTEndpoint()
            );
        }
        return $this->restEndpointGraphQLQuery;
    }

    abstract protected function doGetGraphQLQueryToResolveRESTEndpoint(): string;
}
