<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\RESTAPI\Hooks\RESTFields;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;

class CustomPostHookSet extends AbstractHookSet
{
    const COMMENT_RESTFIELDS = 'comments.id|content';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(AbstractCustomPostRESTEntryComponentRoutingProcessor::class),
            $this->hookGraphQLQueryToResolveRESTEndpoint(...)
        );
    }

    public function hookGraphQLQueryToResolveRESTEndpoint($restEndpointGraphQLQuery): string
    {
        return $restEndpointGraphQLQuery . ',' . self::COMMENT_RESTFIELDS;
    }
}
