<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION = 'forminput-buttongroup-postsection';
    public const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS = 'forminput-buttongroup-postsections';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION],
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return TranslationAPIFacade::getInstance()->__('Section', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return GD_FormInput_PostSection::class;

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($module);
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getDbobjectField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return 'mainCategory';

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return 'catsByName';
        }

        return parent::getDbobjectField($module);
    }
}



