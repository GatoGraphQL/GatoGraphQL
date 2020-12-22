<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\Conditional\RESTAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;

class CustomPostHooks extends AbstractHookSet
{
    const AUTHOR_RESTFIELDS = 'author.id|name|url';

    protected function init()
    {
        $this->hooksAPI->addFilter(
            EntryRouteModuleProcessorHelpers::HOOK_REST_FIELDS,
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::AUTHOR_RESTFIELDS;
    }
}
