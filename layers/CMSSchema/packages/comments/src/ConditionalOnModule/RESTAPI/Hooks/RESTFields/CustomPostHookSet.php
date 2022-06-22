<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\RESTAPI\Hooks\RESTFields;

use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\Hooks\AbstractRESTHookSet;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors\AbstractCustomPostRESTEntryComponentRoutingProcessor;

class CustomPostHookSet extends AbstractRESTHookSet
{
    const COMMENT_RESTFIELDS = 'comments { id content }';

    protected function getHookName(): string
    {
        return HookHelpers::getHookName(AbstractCustomPostRESTEntryComponentRoutingProcessor::class);
    }

    protected function getGraphQLFieldsToAppend(): string
    {
        return self::COMMENT_RESTFIELDS;
    }
}
