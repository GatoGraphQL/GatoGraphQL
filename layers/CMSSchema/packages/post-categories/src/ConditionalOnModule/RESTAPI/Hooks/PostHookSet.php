<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnModule\RESTAPI\Hooks;

use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\Posts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\EntryComponentRoutingProcessor;
use PoPAPI\RESTAPI\Hooks\AbstractRESTHookSet;

class PostHookSet extends AbstractRESTHookSet
{
    const CATEGORY_RESTFIELDS = 'categories { id name url }';

    protected function getHookName(): string
    {
        return HookHelpers::getHookName(EntryComponentRoutingProcessor::class);
    }

    protected function getGraphQLFieldsToAppend(): string
    {
        return self::CATEGORY_RESTFIELDS;
    }
}
