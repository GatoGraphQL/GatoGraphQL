<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes;
use PoPSchema\CustomPosts\Routing\RouteNatures;

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
        $nature = App::getState('nature');

        // Properties specific to each nature
        switch ($nature) {
            case RouteNatures::CUSTOMPOST:
                // Single may depend on its post_type and category
                // Post and Event may be different
                // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
                // By default, we check for post type but not for categories
                $component_types = (array) $this->getHooksAPI()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                    array(
                        ModelInstanceComponentTypes::SINGLE_CUSTOMPOST,
                    )
                );
                if (in_array(ModelInstanceComponentTypes::SINGLE_CUSTOMPOST, $component_types)) {
                    $customPostType = App::getState(['routing', 'queried-object-post-type']);
                    $components[] = $this->__('post type:', 'pop-engine') . $customPostType;
                }
                break;
        }
        return $components;
    }
}
