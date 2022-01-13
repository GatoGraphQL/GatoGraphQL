<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoPSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;

class CustomPostHookSet extends AbstractHookSet
{
    const AUTHOR_RESTFIELDS = 'author.id|name|url';

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookHelpers::getHookName(AbstractCustomPostRESTEntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::AUTHOR_RESTFIELDS;
    }
}
