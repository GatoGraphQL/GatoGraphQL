<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoPSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHookSet extends AbstractHookSet
{
    const TAG_RESTFIELDS = 'tags.id|name|url';

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookHelpers::getHookName(EntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::TAG_RESTFIELDS;
    }
}
