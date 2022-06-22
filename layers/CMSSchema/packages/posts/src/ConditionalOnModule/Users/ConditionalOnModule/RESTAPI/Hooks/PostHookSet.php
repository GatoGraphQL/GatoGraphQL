<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Users\ConditionalOnModule\RESTAPI\Hooks;

use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\Hooks\AbstractRESTHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\EntryComponentRoutingProcessor;

class PostHookSet extends AbstractRESTHookSet
{
    public final const USER_RESTFIELDS = 'posts { id title date url }';

    protected function getHookName(): string
    {
        return HookHelpers::getHookName(EntryComponentRoutingProcessor::class);
    }

    protected function getGraphQLFieldsToAppend(): string
    {
        return self::USER_RESTFIELDS;
    }
}
