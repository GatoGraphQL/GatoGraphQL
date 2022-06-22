<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ConditionalOnModule\RESTAPI\Hooks;

use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\Hooks\AbstractRESTHookSet;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;

class CustomPostHookSet extends AbstractRESTHookSet
{
    const AUTHOR_RESTFIELDS = 'author { id name url }';

    protected function getHookName(): string
    {
        return HookHelpers::getHookName(AbstractCustomPostRESTEntryComponentRoutingProcessor::class);
    }

    protected function getGraphQLFieldsToAppend(): string
    {
        return self::AUTHOR_RESTFIELDS;
    }
}
