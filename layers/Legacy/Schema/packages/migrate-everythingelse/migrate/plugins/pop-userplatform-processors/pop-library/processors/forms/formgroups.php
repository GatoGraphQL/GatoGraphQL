<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_CUP_SHORTDESCRIPTION = 'forminputgroup-cup-shortdescription';
    public final const COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK = 'forminputgroup-cup-facebook';
    public final const COMPONENT_FORMINPUTGROUP_CUP_TWITTER = 'forminputgroup-cup-twitter';
    public final const COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN = 'forminputgroup-cup-linkedin';
    public final const COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE = 'forminputgroup-cup-youtube';
    public final const COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM = 'forminputgroup-cup-instagram';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUTGROUP_CUP_SHORTDESCRIPTION,
            self::COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK,
            self::COMPONENT_FORMINPUTGROUP_CUP_TWITTER,
            self::COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN,
            self::COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE,
            self::COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getLabel($component, $props);

        $icons = array(
            self::COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK => 'fa-facebook',
            self::COMPONENT_FORMINPUTGROUP_CUP_TWITTER => 'fa-twitter',
            self::COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN => 'fa-linkedin',
            self::COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE => 'fa-youtube',
            self::COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM => 'fa-instagram',
        );

        if ($icon = $icons[$component->name] ?? null) {
            $ret = sprintf(
                '<i class="fa fa-fw %s"></i>%s',
                $icon,
                $ret
            );
        }

        return $ret;
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_CUP_SHORTDESCRIPTION => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_SHORTDESCRIPTION],
            self::COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_FACEBOOK],
            self::COMPONENT_FORMINPUTGROUP_CUP_TWITTER => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_TWITTER],
            self::COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_LINKEDIN],
            self::COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_YOUTUBE],
            self::COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::COMPONENT_FORMINPUT_CUP_INSTAGRAM],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Override the placeholders
        $placeholders = array(
            self::COMPONENT_FORMINPUTGROUP_CUP_SHORTDESCRIPTION => TranslationAPIFacade::getInstance()->__('Define yourself in just 1 line...', 'pop-coreprocessors'),
            self::COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK => 'https://www.facebook.com/...',
            self::COMPONENT_FORMINPUTGROUP_CUP_TWITTER => 'https://twitter.com/...',
            self::COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN => 'https://www.linkedin.com/...',
            self::COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE => 'https://www.youtube.com/...',
            self::COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM => 'https://www.instagram.com/...',
        );
        if ($placeholder = $placeholders[$component->name] ?? null) {
            $component = $this->getComponentSubcomponent($component);
            $this->setProp($component, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($component, $props);
    }
}



