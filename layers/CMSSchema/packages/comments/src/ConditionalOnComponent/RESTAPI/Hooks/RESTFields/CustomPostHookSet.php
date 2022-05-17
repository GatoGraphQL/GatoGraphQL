<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\RESTAPI\Hooks\RESTFields;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\RouteModuleProcessors\AbstractCustomPostRESTEntryRouteModuleProcessor;

class CustomPostHookSet extends AbstractHookSet
{
    const COMMENT_RESTFIELDS = 'comments.id|content';

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookName(AbstractCustomPostRESTEntryRouteModuleProcessor::class),
            $this->getRESTFields(...)
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::COMMENT_RESTFIELDS;
    }
}
