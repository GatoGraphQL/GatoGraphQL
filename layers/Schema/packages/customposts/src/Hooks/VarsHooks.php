<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoP\ComponentModel\State\ApplicationState;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        $nature = $vars['nature'];

        // Properties specific to each nature
        switch ($nature) {
            case RouteNatures::CUSTOMPOST:
                // Single may depend on its post_type and category
                // Post and Event may be different
                // Announcements and Articles (Posts), or Past Event and (Upcoming) Event may be different
                // By default, we check for post type but not for categories
                $component_types = (array)HooksAPIFacade::getInstance()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                    array(
                        \PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes::SINGLE_CUSTOMPOST,
                    )
                );
                if (in_array(\PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes::SINGLE_CUSTOMPOST, $component_types)) {
                    $customPostType = $vars['routing-state']['queried-object-post-type'];
                    $components[] =
                        TranslationAPIFacade::getInstance()->__('post type:', 'pop-engine')
                        . $customPostType;
                }
                break;
        }
        return $components;
    }
}
