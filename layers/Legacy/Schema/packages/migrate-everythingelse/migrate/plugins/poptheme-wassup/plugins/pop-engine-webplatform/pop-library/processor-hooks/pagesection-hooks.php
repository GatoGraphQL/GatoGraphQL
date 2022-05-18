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

    public function initModelPropsHover(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];

        foreach ($processor->getSubComponents($component) as $subComponent) {
            // Needed to erase previous feedback messages when a pageSection opens. Eg: Reset password
            $processor->mergeJsmethodsProp([$subComponent], $props, array('closeFeedbackMessagesOnPageSectionOpen'));
        }

        $subComponents = array(
            [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::COMPONENT_BLOCK_LOGIN],
            [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::COMPONENT_BLOCK_LOGOUT],
        );
        foreach ($subComponents as $subComponent) {
            $processor->mergeJsmethodsProp(
                $subComponent,
                $props,
                array(
                    'closePageSectionOnSuccess',
                )
            );
            $processor->mergeProp(
                $subComponent,
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
