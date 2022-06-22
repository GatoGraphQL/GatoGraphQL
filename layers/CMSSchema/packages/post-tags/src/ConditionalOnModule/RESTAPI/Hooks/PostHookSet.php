<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ConditionalOnModule\RESTAPI\Hooks;

use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\Hooks\AbstractRESTHookSet;
use PoPCMSSchema\Posts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\EntryComponentRoutingProcessor;

class PostHookSet extends AbstractRESTHookSet
{
    const TAG_RESTFIELDS = 'tags { id name url }';

    protected function getHookName(): string
    {
        return HookHelpers::getHookName(EntryComponentRoutingProcessor::class);
    }

    protected function getGraphQLFieldsToAppend(): string
    {
        return self::TAG_RESTFIELDS;
    }
}
