<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Pages\Constants\ModelInstanceComponentTypes;
use PoPCMSSchema\Pages\Routing\RequestNature;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_ELEMENTS_RESULT,
            $this->getModelInstanceElementsFromAppState(...)
        );
    }

    /**
     * @return string[]
     * @param string[] $elements
     */
    public function getModelInstanceElementsFromAppState(array $elements): array
    {
        switch (App::getState('nature')) {
            case RequestNature::PAGE:
                // @todo convert the hook from string to const, then re-enable
                // $component_types = App::applyFilters(
                //     '\PoPCMSSchema\Pages\ModelInstanceProcessor_Utils:components_from_vars:type:page',
                //     []
                // );
                $component_types = [];
                if (in_array(ModelInstanceComponentTypes::SINGLE_PAGE, $component_types)) {
                    $page_id = App::getState(['routing', 'queried-object-id']);
                    $elements[] = $this->__('page id:', 'pop-engine') . $page_id;
                }
                break;
        }
        return $elements;
    }
}
