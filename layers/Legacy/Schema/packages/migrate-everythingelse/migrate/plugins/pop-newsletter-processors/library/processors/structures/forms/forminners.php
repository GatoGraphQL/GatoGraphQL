<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Newsletter_Module_Processor_GFFormInners extends PoP_Module_Processor_FormInnersBase
{
    public const MODULE_FORMINNER_NEWSLETTER = 'forminner-newsletter';
    public const MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION = 'forminner-newsletterunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_NEWSLETTER],
            [self::class, self::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
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
        $ret = \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Module_Processor_GFFormInners:layouts',
            $ret,
            $module
        );

        return $ret;
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION:
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                $inputs = array(
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
                    [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE],
                );
                foreach ($inputs as $input) {
                    $this->mergeJsmethodsProp($input, $props, array('fillURLParamInput'));

                    $input_name = $moduleprocessor_manager->getProcessor($input)->getName($input);
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

        parent::initWebPlatformModelProps($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {

        // Allow Gravity Forms to set props on its added fields
        \PoP\Root\App::getHookManager()->doAction(
            'PoP_Module_Processor_GFFormInners:init-props',
            $module,
            array(&$props),
            $this
        );

        parent::initModelProps($module, $props);
    }
}



