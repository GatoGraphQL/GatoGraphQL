<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\Pages\Constants\ModelInstanceComponentTypes;
use PoPSchema\Pages\Routing\RouteNatures;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        switch (App::getState('nature')) {
            case RouteNatures::PAGE:
                $component_types = $this->getHooksAPI()->applyFilters(
                    '\PoPSchema\Pages\ModelInstanceProcessor_Utils:components_from_vars:type:page',
                    []
                );
                if (in_array(ModelInstanceComponentTypes::SINGLE_PAGE, $component_types)) {
                    $page_id = App::getState(['routing', 'queried-object-id']);
                    $components[] = $this->__('page id:', 'pop-engine') . $page_id;
                }
                break;
        }
        return $components;
    }
}
