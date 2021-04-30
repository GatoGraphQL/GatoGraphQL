<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;

class PostHooks extends AbstractHookSet
{
    const TAG_RESTFIELDS = 'tags.id|name|url';

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            EntryRouteModuleProcessorHelpers::HOOK_REST_FIELDS,
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::TAG_RESTFIELDS;
    }
}
