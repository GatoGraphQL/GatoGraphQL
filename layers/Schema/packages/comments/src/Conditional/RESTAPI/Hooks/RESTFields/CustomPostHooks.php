<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Conditional\RESTAPI\Hooks\RESTFields;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;

class CustomPostHooks extends AbstractHookSet
{
    const COMMENT_RESTFIELDS = 'comments.id|content';

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            EntryRouteModuleProcessorHelpers::HOOK_REST_FIELDS,
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::COMMENT_RESTFIELDS;
    }
}
