<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateProfileTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_CUP_SHORTDESCRIPTION = 'forminput-cup-shortdescription';
    public final const MODULE_FORMINPUT_CUP_FACEBOOK = 'forminput-cup-facebook';
    public final const MODULE_FORMINPUT_CUP_TWITTER = 'forminput-cup-twitter';
    public final const MODULE_FORMINPUT_CUP_LINKEDIN = 'forminput-cup-linkedin';
    public final const MODULE_FORMINPUT_CUP_YOUTUBE = 'forminput-cup-youtube';
    public final const MODULE_FORMINPUT_CUP_INSTAGRAM = 'forminput-cup-instagram';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_CUP_SHORTDESCRIPTION],
            [self::class, self::MODULE_FORMINPUT_CUP_FACEBOOK],
            [self::class, self::MODULE_FORMINPUT_CUP_TWITTER],
            [self::class, self::MODULE_FORMINPUT_CUP_LINKEDIN],
            [self::class, self::MODULE_FORMINPUT_CUP_YOUTUBE],
            [self::class, self::MODULE_FORMINPUT_CUP_INSTAGRAM],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_SHORTDESCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Short description', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUP_FACEBOOK:
                return TranslationAPIFacade::getInstance()->__('Facebook URL', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUP_TWITTER:
                return TranslationAPIFacade::getInstance()->__('Twitter URL', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUP_LINKEDIN:
                return TranslationAPIFacade::getInstance()->__('LinkedIn URL', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUP_YOUTUBE:
                return TranslationAPIFacade::getInstance()->__('Youtube URL', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_CUP_INSTAGRAM:
                return TranslationAPIFacade::getInstance()->__('Instagram URL', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_CUP_SHORTDESCRIPTION:
                return 'shortDescription';

            case self::MODULE_FORMINPUT_CUP_FACEBOOK:
                return 'facebook';

            case self::MODULE_FORMINPUT_CUP_TWITTER:
                return 'twitter';

            case self::MODULE_FORMINPUT_CUP_LINKEDIN:
                return 'linkedin';

            case self::MODULE_FORMINPUT_CUP_YOUTUBE:
                return 'youtube';

            case self::MODULE_FORMINPUT_CUP_INSTAGRAM:
                return 'instagram';
        }

        return parent::getDbobjectField($module);
    }


    public function initModelProps(array $module, array &$props): void
    {
        // Remove the html code from the placeholder
        $placeholders = array(
            self::MODULE_FORMINPUT_CUP_FACEBOOK => TranslationAPIFacade::getInstance()->__('Facebook URL', 'pop-coreprocessors'),
            self::MODULE_FORMINPUT_CUP_TWITTER => TranslationAPIFacade::getInstance()->__('Twitter URL', 'pop-coreprocessors'),
            self::MODULE_FORMINPUT_CUP_LINKEDIN => TranslationAPIFacade::getInstance()->__('LinkedIn URL', 'pop-coreprocessors'),
            self::MODULE_FORMINPUT_CUP_YOUTUBE => TranslationAPIFacade::getInstance()->__('Youtube URL', 'pop-coreprocessors'),
            self::MODULE_FORMINPUT_CUP_INSTAGRAM => TranslationAPIFacade::getInstance()->__('Instagram URL', 'pop-coreprocessors'),
        );
        if ($placeholder = $placeholders[$module[1]] ?? null) {
            $this->setProp($module, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($module, $props);
    }
}



