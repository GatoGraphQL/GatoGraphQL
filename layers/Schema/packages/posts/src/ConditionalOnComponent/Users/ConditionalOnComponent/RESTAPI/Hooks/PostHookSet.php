<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnComponent\Users\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoPSchema\Users\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHookSet extends AbstractHookSet
{
    public const USER_RESTFIELDS = 'posts.id|title|date|url';

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookHelpers::getHookName(EntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::USER_RESTFIELDS;
    }
}
