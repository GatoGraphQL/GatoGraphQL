<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoPSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHookSet extends AbstractHookSet
{
    const CATEGORY_RESTFIELDS = 'categories.id|name|url';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(EntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::CATEGORY_RESTFIELDS;
    }
}
