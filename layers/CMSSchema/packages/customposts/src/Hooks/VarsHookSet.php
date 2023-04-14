<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPosts\Constants\ModelInstanceComponentTypes;
use PoPCMSSchema\CustomPosts\Routing\RequestNature;

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
        $nature = App::getState('nature');

        // Properties specific to each nature
        switch ($nature) {
            case RequestNature::CUSTOMPOST:
                // Single may depend on its post_type and category
                // Post and Event may be different
                // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
                // By default, we check for post type but not for categories
                // @todo convert the hook from string to const, then re-enable
                // $component_types = (array) App::applyFilters(
                //     '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                //     array(
                //         ModelInstanceComponentTypes::SINGLE_CUSTOMPOST,
                //     )
                // );
                $component_types = array(
                    ModelInstanceComponentTypes::SINGLE_CUSTOMPOST,
                );
                if (in_array(ModelInstanceComponentTypes::SINGLE_CUSTOMPOST, $component_types)) {
                    $customPostType = App::getState(['routing', 'queried-object-post-type']);
                    $elements[] = $this->__('post type:', 'pop-engine') . $customPostType;
                }
                break;
        }
        return $elements;
    }
}
