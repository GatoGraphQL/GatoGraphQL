<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ConditionalOnComponent\RESTAPI\RouteModuleProcessors;

use PoP\RESTAPI\Helpers\HookHelpers;
use PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor;

class AbstractCustomPostRESTEntryRouteModuleProcessor extends AbstractRESTEntryRouteModuleProcessor
{
    protected function getInitialRESTFields(): string
    {
        return 'id|title|date|url|content';
    }

    /**
     * Add an additional hook on this abstract class
     */
    public function getRESTFieldsQuery(): string
    {
        if (is_null($this->restFieldsQuery)) {
            $this->restFieldsQuery = (string) \PoP\Root\App::getHookManager()->applyFilters(
                HookHelpers::getHookName(__CLASS__),
                parent::getRESTFieldsQuery()
            );
        }
        return $this->restFieldsQuery;
    }
}
