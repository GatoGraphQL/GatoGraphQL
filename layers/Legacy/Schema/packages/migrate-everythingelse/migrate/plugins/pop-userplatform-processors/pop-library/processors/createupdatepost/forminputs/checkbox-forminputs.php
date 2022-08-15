<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs extends PoP_Module_Processor_BooleanCheckboxFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_KEEPASDRAFT = 'forminput-keepasdraft';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT:
                return TranslationAPIFacade::getInstance()->__('Keep as draft?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT:
                return /* @todo Re-do this code! Left undone */ new Field('isStatus', ['status' => Status::DRAFT], 'is-draft');
        }

        return parent::getDbobjectField($component);
    }
}



