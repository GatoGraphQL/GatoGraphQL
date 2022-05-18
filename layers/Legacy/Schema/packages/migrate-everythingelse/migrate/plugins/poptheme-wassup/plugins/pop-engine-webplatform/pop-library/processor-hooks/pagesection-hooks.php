<?php

class PoPTheme_Wassup_WebPlatform_PageSectionHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomTabPanePageSections:get_props_block_initial:hover',
            $this->initModelPropsHover(...),
            10,
            3
        );
    }

    public function initModelPropsHover(array $componentVariation, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];

        foreach ($processor->getSubComponentVariations($componentVariation) as $submodule) {
            // Needed to erase previous feedback messages when a pageSection opens. Eg: Reset password
            $processor->mergeJsmethodsProp([$submodule], $props, array('closeFeedbackMessagesOnPageSectionOpen'));
        }

        $subComponentVariations = array(
            [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN],
            [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGOUT],
        );
        foreach ($subComponentVariations as $submodule) {
            $processor->mergeJsmethodsProp(
                $submodule,
                $props,
                array(
                    'closePageSectionOnSuccess',
                )
            );
            $processor->mergeProp(
                $submodule,
                $props,
                'params',
                array(
                    'data-closetime' => 1500,
                )
            );
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_WebPlatform_PageSectionHooks();
