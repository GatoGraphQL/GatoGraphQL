<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\CustomPosts\Constants\ModelInstanceComponentTypes;
use PoPSchema\CustomPosts\Routing\RouteNatures;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

class VarsHookSet extends AbstractHookSet
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
        $this->getHooksAPI()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
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
                $component_types = (array) $this->getHooksAPI()->applyFilters(
                    '\PoP\ComponentModel\ModelInstanceProcessor_Utils:components_from_vars:type:single',
                    array(
                        ModelInstanceComponentTypes::SINGLE_CUSTOMPOST,
                    )
                );
                if (in_array(ModelInstanceComponentTypes::SINGLE_CUSTOMPOST, $component_types)) {
                    $customPostType = $vars['routing-state']['queried-object-post-type'];
                    $components[] = $this->__('post type:', 'pop-engine') . $customPostType;
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
        // Set additional properties based on the nature
        [&$vars] = $vars_in_array;
        $nature = $vars['nature'];
        $vars['routing-state']['is-custompost'] = $nature == RouteNatures::CUSTOMPOST;

        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature == RouteNatures::CUSTOMPOST) {
            $customPostID = $vars['routing-state']['queried-object-id'];
            $vars['routing-state']['queried-object-post-type'] = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        }
    }
}
