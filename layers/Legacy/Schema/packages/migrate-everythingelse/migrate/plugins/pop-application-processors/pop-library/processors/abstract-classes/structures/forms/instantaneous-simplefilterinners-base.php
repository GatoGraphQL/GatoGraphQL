<?php

abstract class PoP_Module_Processor_InstantaneousSimpleFilterInnersBase extends PoP_Module_Processor_SimpleFilterInnersBase
{

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function getSubmitbtnModule(array $component)
    {

        // Use a special Search button, so it doesn't share the $props with the Search from the filter
        return [PoPTheme_Wassup_Module_Processor_SubmitButtons::class, PoPTheme_Wassup_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_INSTANTANEOUSSEARCH];
    }

    public function getTriggerInternaltarget(array $component, array &$props)
    {
        return null;
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {

        // When clicking on any button, already submit the form
        if ($submit_btn = $this->getSubmitbtnModule($component)) {
            // $trigger_component can only be the Filter and not the FilterInner, because FilterInner has no id, which is needed for previouscomponents-ids
            if ($trigger_component = $this->getProp($component, $props, 'trigger-component')) {
                // Execute JS and set all needed params
                $this->mergeJsmethodsProp($submit_btn, $props, array('onActionThenClick'));
                $this->mergeProp(
                    $submit_btn,
                    $props,
                    'previouscomponents-ids',
                    array(
                        'data-triggertarget' => $trigger_component,
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
                if ($internal_target = $this->getTriggerInternaltarget($component, $props)) {
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

        parent::initWebPlatformModelProps($component, $props);
    }
    public function initModelProps(array $component, array &$props): void
    {

        // When clicking on any button, already submit the form
        if ($submit_btn = $this->getSubmitbtnModule($component)) {
            // Do not show
            $this->appendProp($submit_btn, $props, 'class', 'hidden');
        }
        parent::initModelProps($component, $props);
    }
}
