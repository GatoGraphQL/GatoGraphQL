<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Newsletter_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_NEWSLETTER = 'forminner-newsletter';
    public final const COMPONENT_FORMINNER_NEWSLETTERUNSUBSCRIPTION = 'forminner-newsletterunsubscription';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_NEWSLETTER,
            self::COMPONENT_FORMINNER_NEWSLETTERUNSUBSCRIPTION,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_FORMINNER_NEWSLETTER:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NEWSLETTEREMAIL],
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NEWSLETTERNAME],
                        [PoP_Newsletter_Module_Processor_SubmitButtons::class, PoP_Newsletter_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_SUBSCRIBE],
                    )
                );
                break;

            case self::COMPONENT_FORMINNER_NEWSLETTERUNSUBSCRIPTION:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NEWSLETTEREMAILVERIFICATIONEMAIL],
                        [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
                        [PoP_Newsletter_Module_Processor_SubmitButtons::class, PoP_Newsletter_Module_Processor_SubmitButtons::COMPONENT_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION],
                    )
                );
                break;
        }

        // Allow Gravity Forms to add extra fields
        $ret = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_GFFormInners:layouts',
            $ret,
            $component
        );

        return $ret;
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_NEWSLETTERUNSUBSCRIPTION:
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $inputs = array(
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
                );
                foreach ($inputs as $input) {
                    $this->mergeJsmethodsProp($input, $props, array('fillURLParamInput'));

                    $input_name = $componentprocessor_manager->getComponentProcessor($input)->getName($input);
                    $this->mergeProp(
                        $input,
                        $props,
                        'params',
                        array(
                            'data-urlparam' => $input_name
                        )
                    );
                }
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Allow Gravity Forms to set props on its added fields
        \PoP\Root\App::doAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            $component,
            array(&$props),
            $this
        );

        parent::initModelProps($component, $props);
    }
}



