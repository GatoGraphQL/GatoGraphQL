<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Hooks;

use PoPSchema\Pages\Constants\ModelInstanceComponentTypes;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Pages\Routing\RouteNatures;

class VarsHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        switch ($vars['nature']) {
            case RouteNatures::PAGE:
                $component_types = $this->hooksAPI->applyFilters(
                    '\PoPSchema\Pages\ModelInstanceProcessor_Utils:components_from_vars:type:page',
                    []
                );
                if (in_array(ModelInstanceComponentTypes::SINGLE_PAGE, $component_types)) {
                    $page_id = $vars['routing-state']['queried-object-id'];
                    $components[] = $this->translationAPI->__('page id:', 'pop-engine') . $page_id;
                }
                break;
        }
        return $components;
    }
}
