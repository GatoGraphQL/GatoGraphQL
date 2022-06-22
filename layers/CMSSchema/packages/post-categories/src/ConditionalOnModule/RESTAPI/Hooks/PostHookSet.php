<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnModule\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\Posts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\EntryComponentRoutingProcessor;

class PostHookSet extends AbstractHookSet
{
    const CATEGORY_RESTFIELDS = 'categories.id|name|url';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(EntryComponentRoutingProcessor::class),
            $this->hookGraphQLQueryToResolveRESTEndpoint(...)
        );
    }

    public function hookGraphQLQueryToResolveRESTEndpoint($restEndpointGraphQLQuery): string
    {
        return $restEndpointGraphQLQuery . ',' . self::CATEGORY_RESTFIELDS;
    }
}
