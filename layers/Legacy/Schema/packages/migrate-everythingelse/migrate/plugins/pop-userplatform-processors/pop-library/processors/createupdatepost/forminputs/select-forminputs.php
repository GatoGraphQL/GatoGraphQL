<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostSelectFormInputs extends PoP_Module_Processor_SelectFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_STATUS = 'forminput-cup-status';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS = 'forminput-linkaccess';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_CUP_STATUS,
            self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('Publishing status', 'poptheme-wassup');

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_STATUS:
                return GD_FormInput_ModeratedStatusDescription::class;

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return GD_FormInput_LinkAccessDescription::class;
        }

        return parent::getInputClass($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_STATUS:
                $this->appendProp($component, $props, 'class', 'form-input-status');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_STATUS:
                return 'status';

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($component);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_STATUS:
                $this->addJsmethod($ret, 'manageStatus');
                break;
        }

        return $ret;
    }
}



