<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_UserStance_LayoutHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomPreviewPostLayoutsBase:simpleviewfeed_bottom_modules',
            array($this, 'getFeedBottomSubmodules')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_modules',
            array($this, 'getFeedBottomSubmodules')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimage:modules',
            array($this, 'singleComponents')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules',
            array($this, 'singleComponents')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'Wassup_Module_Processor_UserPostInteractionLayouts:userpostinteraction:layouts',
            array($this, 'userpostinteraction')
        );
    }

    public function userpostinteraction($layouts)
    {

        // Only if it is not single. In that case, we add the block to directly add the Thought
        // Add the "What do you think about TPP?" before the userpostinteraction layouts
        if (!\PoP\Root\App::getState(['routing', 'is-custompost'])) {
            array_unshift(
                $layouts,
                [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE]
            );
        }
        return $layouts;
    }

    public function singleComponents($layouts)
    {

        // Add the poststance at the end
        $layouts[] = [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT];
        return $layouts;
    }

    public function getFeedBottomSubmodules($layouts)
    {

        // Add the poststance at the beginning
        array_unshift(
            $layouts,
            [UserStance_Module_Processor_WidgetWrappers::class, UserStance_Module_Processor_WidgetWrappers::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT]
        );
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_LayoutHooks();
