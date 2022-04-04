<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnComponent\Users\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\Users\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHookSet extends AbstractHookSet
{
    public final const USER_RESTFIELDS = 'posts.id|title|date|url';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(EntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::USER_RESTFIELDS;
    }
}
