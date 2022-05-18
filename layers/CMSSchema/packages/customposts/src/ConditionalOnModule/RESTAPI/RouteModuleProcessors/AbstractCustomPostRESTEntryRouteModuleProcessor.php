<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ConditionalOnModule\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\RESTAPI\Helpers\HookHelpers;
use PoPAPI\RESTAPI\ComponentRoutingProcessors\AbstractRESTEntryComponentRoutingProcessor;

class AbstractCustomPostRESTEntryComponentRoutingProcessor extends AbstractRESTEntryComponentRoutingProcessor
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
            $this->restFieldsQuery = (string) App::applyFilters(
                HookHelpers::getHookName(__CLASS__),
                parent::getRESTFieldsQuery()
            );
        }
        return $this->restFieldsQuery;
    }
}
