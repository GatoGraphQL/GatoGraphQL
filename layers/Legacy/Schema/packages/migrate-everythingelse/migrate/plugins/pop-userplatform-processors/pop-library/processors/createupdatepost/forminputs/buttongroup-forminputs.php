<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION = 'forminput-buttongroup-postsection';
    public final const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS = 'forminput-buttongroup-postsections';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION],
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return TranslationAPIFacade::getInstance()->__('Section', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return GD_FormInput_PostSection::class;

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getDbobjectField(array $component): ?string
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return 'mainCategory';

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return 'catsByName';
        }

        return parent::getDbobjectField($component);
    }
}



