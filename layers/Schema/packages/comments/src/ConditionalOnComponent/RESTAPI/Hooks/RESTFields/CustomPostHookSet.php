<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\RESTAPI\Hooks\RESTFields;

use PoP\Root\Hooks\AbstractHookSet;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoPSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;

class CustomPostHookSet extends AbstractHookSet
{
    const COMMENT_RESTFIELDS = 'comments.id|content';

    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            HookHelpers::getHookName(AbstractCustomPostRESTEntryRouteModuleProcessor::class),
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::COMMENT_RESTFIELDS;
    }
}
