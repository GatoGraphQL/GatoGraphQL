<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION = 'forminput-buttongroup-postsection';
    public final const COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS = 'forminput-buttongroup-postsections';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION,
            self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return TranslationAPIFacade::getInstance()->__('Section', 'poptheme-wassup');

            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return GD_FormInput_PostSection::class;

            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return 'mainCategory';

            case self::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return 'catsByName';
        }

        return parent::getDbobjectField($component);
    }
}



