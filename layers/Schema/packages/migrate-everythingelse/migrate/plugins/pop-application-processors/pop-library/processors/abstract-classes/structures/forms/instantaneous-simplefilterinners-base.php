<?php

abstract class PoP_Module_Processor_InstantaneousSimpleFilterInnersBase extends PoP_Module_Processor_SimpleFilterInnersBase
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function getSubmitbtnModule(array $module)
    {

        // Use a special Search button, so it doesn't share the $props with the Search from the filter
        return [PoPTheme_Wassup_Module_Processor_SubmitButtons::class, PoPTheme_Wassup_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_INSTANTANEOUSSEARCH];
    }

    public function getTriggerInternaltarget(array $module, array &$props)
    {
        return null;
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {

        // When clicking on any button, already submit the form
        if ($submit_btn = $this->getSubmitbtnModule($module)) {
            // $trigger_module can only be the Filter and not the FilterInner, because FilterInner has no id, which is needed for previousmodules-ids
            if ($trigger_module = $this->getProp($module, $props, 'trigger-module')) {
                // Execute JS and set all needed params
                $this->mergeJsmethodsProp($submit_btn, $props, array('onActionThenClick'));
                $this->mergeProp(
                    $submit_btn,
                    $props,
                    'previousmodules-ids',
                    array(
                        'data-triggertarget' => $trigger_module,
                    )
                );
                $this->mergeProp(
                    $submit_btn,
                    $props,
                    'params',
                    array(
                        'data-trigger-action' => 'change',
                    )
                );
                if ($internal_target = $this->getTriggerInternaltarget($module, $props)) {
                    $this->mergeProp(
                        $submit_btn,
                        $props,
                        'params',
                        array(
                            'data-triggertarget-internal' => $internal_target,
                        )
                    );
                }
            }
        }

        parent::initWebPlatformModelProps($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {

        // When clicking on any button, already submit the form
        if ($submit_btn = $this->getSubmitbtnModule($module)) {
            // Do not show
            $this->appendProp($submit_btn, $props, 'class', 'hidden');
        }
        parent::initModelProps($module, $props);
    }
}
