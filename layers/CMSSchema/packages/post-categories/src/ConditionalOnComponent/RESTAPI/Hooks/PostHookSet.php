<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\Posts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHookSet extends AbstractHookSet
{
    const CATEGORY_RESTFIELDS = 'categories.id|name|url';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(EntryRouteModuleProcessor::class),
            $this->getRESTFields(...)
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::CATEGORY_RESTFIELDS;
    }
}
