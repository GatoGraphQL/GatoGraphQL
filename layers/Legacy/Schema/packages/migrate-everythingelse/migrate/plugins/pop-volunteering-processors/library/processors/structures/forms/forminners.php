<?php

class PoP_Volunteering_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_VOLUNTEER = 'forminner-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_VOLUNTEER],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_VOLUNTEER:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Volunteering_Module_Processor_FormComponentGroups::class, PoP_Volunteering_Module_Processor_FormComponentGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_VOLUNTEER],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_NAME],
                        [PoP_Forms_Module_Processor_FormGroups::class, PoP_Forms_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_EMAIL],
                        [PoP_Volunteering_Module_Processor_FormGroups::class, PoP_Volunteering_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_PHONE],
                        [PoP_Volunteering_Module_Processor_FormGroups::class, PoP_Volunteering_Module_Processor_FormGroups::COMPONENT_FORMINPUTGROUP_WHYVOLUNTEER],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
                    )
                );
                if (defined('POP_FORMSWEBPLATFORM_INITIALIZED')) {
                    if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                        array_splice(
                            $ret,
                            array_search(
                                [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
                                $ret
                            ),
                            0,
                            array(
                                [PoP_Captcha_Module_Processor_FormInputGroups::class, PoP_Captcha_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_CAPTCHA],
                            )
                        );
                    }
                }
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



