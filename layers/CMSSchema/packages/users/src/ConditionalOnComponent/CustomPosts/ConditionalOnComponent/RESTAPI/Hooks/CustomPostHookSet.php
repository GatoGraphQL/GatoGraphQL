<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnComponent\CustomPosts\ConditionalOnComponent\RESTAPI\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;

class CustomPostHookSet extends AbstractHookSet
{
    const AUTHOR_RESTFIELDS = 'author.id|name|url';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(AbstractCustomPostRESTEntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::AUTHOR_RESTFIELDS;
    }
}
