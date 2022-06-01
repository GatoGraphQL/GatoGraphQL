<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdateProfileTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_CUP_SHORTDESCRIPTION = 'forminput-cup-shortdescription';
    public final const COMPONENT_FORMINPUT_CUP_FACEBOOK = 'forminput-cup-facebook';
    public final const COMPONENT_FORMINPUT_CUP_TWITTER = 'forminput-cup-twitter';
    public final const COMPONENT_FORMINPUT_CUP_LINKEDIN = 'forminput-cup-linkedin';
    public final const COMPONENT_FORMINPUT_CUP_YOUTUBE = 'forminput-cup-youtube';
    public final const COMPONENT_FORMINPUT_CUP_INSTAGRAM = 'forminput-cup-instagram';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_CUP_SHORTDESCRIPTION],
            [self::class, self::COMPONENT_FORMINPUT_CUP_FACEBOOK],
            [self::class, self::COMPONENT_FORMINPUT_CUP_TWITTER],
            [self::class, self::COMPONENT_FORMINPUT_CUP_LINKEDIN],
            [self::class, self::COMPONENT_FORMINPUT_CUP_YOUTUBE],
            [self::class, self::COMPONENT_FORMINPUT_CUP_INSTAGRAM],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_SHORTDESCRIPTION:
                return TranslationAPIFacade::getInstance()->__('Short description', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUP_FACEBOOK:
                return TranslationAPIFacade::getInstance()->__('Facebook URL', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUP_TWITTER:
                return TranslationAPIFacade::getInstance()->__('Twitter URL', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUP_LINKEDIN:
                return TranslationAPIFacade::getInstance()->__('LinkedIn URL', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUP_YOUTUBE:
                return TranslationAPIFacade::getInstance()->__('Youtube URL', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_CUP_INSTAGRAM:
                return TranslationAPIFacade::getInstance()->__('Instagram URL', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_CUP_SHORTDESCRIPTION:
                return 'shortDescription';

            case self::COMPONENT_FORMINPUT_CUP_FACEBOOK:
                return 'facebook';

            case self::COMPONENT_FORMINPUT_CUP_TWITTER:
                return 'twitter';

            case self::COMPONENT_FORMINPUT_CUP_LINKEDIN:
                return 'linkedin';

            case self::COMPONENT_FORMINPUT_CUP_YOUTUBE:
                return 'youtube';

            case self::COMPONENT_FORMINPUT_CUP_INSTAGRAM:
                return 'instagram';
        }

        return parent::getDbobjectField($component);
    }


    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        // Remove the html code from the placeholder
        $placeholders = array(
            self::COMPONENT_FORMINPUT_CUP_FACEBOOK => TranslationAPIFacade::getInstance()->__('Facebook URL', 'pop-coreprocessors'),
            self::COMPONENT_FORMINPUT_CUP_TWITTER => TranslationAPIFacade::getInstance()->__('Twitter URL', 'pop-coreprocessors'),
            self::COMPONENT_FORMINPUT_CUP_LINKEDIN => TranslationAPIFacade::getInstance()->__('LinkedIn URL', 'pop-coreprocessors'),
            self::COMPONENT_FORMINPUT_CUP_YOUTUBE => TranslationAPIFacade::getInstance()->__('Youtube URL', 'pop-coreprocessors'),
            self::COMPONENT_FORMINPUT_CUP_INSTAGRAM => TranslationAPIFacade::getInstance()->__('Instagram URL', 'pop-coreprocessors'),
        );
        if ($placeholder = $placeholders[$component->name] ?? null) {
            $this->setProp($component, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($component, $props);
    }
}



