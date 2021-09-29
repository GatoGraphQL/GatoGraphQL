<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\Pages\Constants\ModelInstanceComponentTypes;
use PoPSchema\Pages\Routing\RouteNatures;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
        $this->hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
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

    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        // Set additional properties based on the nature: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $nature = $vars['nature'];
        $vars['routing-state']['is-page'] = $nature == RouteNatures::PAGE;
    }
}
