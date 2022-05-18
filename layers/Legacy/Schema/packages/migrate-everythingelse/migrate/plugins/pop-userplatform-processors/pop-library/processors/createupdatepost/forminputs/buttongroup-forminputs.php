<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase
{
    public final const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION = 'forminput-buttongroup-postsection';
    public final const MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS = 'forminput-buttongroup-postsections';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION],
            [self::class, self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return TranslationAPIFacade::getInstance()->__('Section', 'poptheme-wassup');

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return GD_FormInput_PostSection::class;

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function isMultiple(array $componentVariation): bool
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($componentVariation);
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION:
                return 'mainCategory';

            case self::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS:
                return 'catsByName';
        }

        return parent::getDbobjectField($componentVariation);
    }
}



