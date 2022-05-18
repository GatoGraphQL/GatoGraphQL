<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoP_Newsletter_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_NEWSLETTER = 'forminner-newsletter';
    public final const MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION = 'forminner-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_NEWSLETTER],
            [self::class, self::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_FORMINNER_NEWSLETTER:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_NEWSLETTEREMAIL],
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_NEWSLETTERNAME],
                        [PoP_Newsletter_Module_Processor_SubmitButtons::class, PoP_Newsletter_Module_Processor_SubmitButtons::MODULE_GF_SUBMITBUTTON_SUBSCRIBE],
                    )
                );
                break;

            case self::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Newsletter_Module_Processor_FormGroups::class, PoP_Newsletter_Module_Processor_FormGroups::MODULE_FORMINPUTGROUP_NEWSLETTEREMAILVERIFICATIONEMAIL],
                        [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
                        [PoP_Newsletter_Module_Processor_SubmitButtons::class, PoP_Newsletter_Module_Processor_SubmitButtons::MODULE_GF_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION],
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

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION:
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $inputs = array(
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
                );
                foreach ($inputs as $input) {
                    $this->mergeJsmethodsProp($input, $props, array('fillURLParamInput'));

                    $input_name = $componentprocessor_manager->getProcessor($input)->getName($input);
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
    public function initModelProps(array $component, array &$props): void
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



