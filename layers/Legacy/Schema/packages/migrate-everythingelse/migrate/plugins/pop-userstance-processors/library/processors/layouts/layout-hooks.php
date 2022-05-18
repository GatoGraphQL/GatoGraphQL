<?php
use PoP\ComponentModel\State\ApplicationState;

class PoPTheme_UserStance_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomPreviewPostLayoutsBase:simpleviewfeed_bottom_components',
            $this->getFeedBottomSubmodules(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_components',
            $this->getFeedBottomSubmodules(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimage:modules',
            $this->singleComponents(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules',
            $this->singleComponents(...)
        );
        \PoP\Root\App::addFilter(
            'Wassup_Module_Processor_UserPostInteractionLayouts:userpostinteraction:layouts',
            $this->userpostinteraction(...)
        );
    }

    public function userpostinteraction($layouts)
    {

        // Only if it is not single. In that case, we add the block to directly add the Thought
        // Add the "What do you think about TPP?" before the userpostinteraction layouts
        if (!\PoP\Root\App::getState(['routing', 'is-custompost'])) {
            array_unshift(
                $layouts,
                [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE]
            );
        }
        return $layouts;
    }

    public function singleComponents($layouts)
    {

        // Add the poststance at the end
        $layouts[] = [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT];
        return $layouts;
    }

    public function getFeedBottomSubmodules($layouts)
    {

        // Add the poststance at the beginning
        array_unshift(
            $layouts,
            [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT]
        );
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_LayoutHooks();
