<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_CUP_SHORTDESCRIPTION = 'forminputgroup-cup-shortdescription';
    public final const MODULE_FORMINPUTGROUP_CUP_FACEBOOK = 'forminputgroup-cup-facebook';
    public final const MODULE_FORMINPUTGROUP_CUP_TWITTER = 'forminputgroup-cup-twitter';
    public final const MODULE_FORMINPUTGROUP_CUP_LINKEDIN = 'forminputgroup-cup-linkedin';
    public final const MODULE_FORMINPUTGROUP_CUP_YOUTUBE = 'forminputgroup-cup-youtube';
    public final const MODULE_FORMINPUTGROUP_CUP_INSTAGRAM = 'forminputgroup-cup-instagram';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_SHORTDESCRIPTION],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_FACEBOOK],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_TWITTER],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_LINKEDIN],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_YOUTUBE],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_INSTAGRAM],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        $ret = parent::getLabel($module, $props);

        $icons = array(
            self::MODULE_FORMINPUTGROUP_CUP_FACEBOOK => 'fa-facebook',
            self::MODULE_FORMINPUTGROUP_CUP_TWITTER => 'fa-twitter',
            self::MODULE_FORMINPUTGROUP_CUP_LINKEDIN => 'fa-linkedin',
            self::MODULE_FORMINPUTGROUP_CUP_YOUTUBE => 'fa-youtube',
            self::MODULE_FORMINPUTGROUP_CUP_INSTAGRAM => 'fa-instagram',
        );

        if ($icon = $icons[$module[1]] ?? null) {
            $ret = sprintf(
                '<i class="fa fa-fw %s"></i>%s',
                $icon,
                $ret
            );
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_CUP_SHORTDESCRIPTION => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_SHORTDESCRIPTION],
            self::MODULE_FORMINPUTGROUP_CUP_FACEBOOK => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_FACEBOOK],
            self::MODULE_FORMINPUTGROUP_CUP_TWITTER => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_TWITTER],
            self::MODULE_FORMINPUTGROUP_CUP_LINKEDIN => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_LINKEDIN],
            self::MODULE_FORMINPUTGROUP_CUP_YOUTUBE => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_YOUTUBE],
            self::MODULE_FORMINPUTGROUP_CUP_INSTAGRAM => [PoP_Module_Processor_CreateUpdateProfileTextFormInputs::class, PoP_Module_Processor_CreateUpdateProfileTextFormInputs::MODULE_FORMINPUT_CUP_INSTAGRAM],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Override the placeholders
        $placeholders = array(
            self::MODULE_FORMINPUTGROUP_CUP_SHORTDESCRIPTION => TranslationAPIFacade::getInstance()->__('Define yourself in just 1 line...', 'pop-coreprocessors'),
            self::MODULE_FORMINPUTGROUP_CUP_FACEBOOK => 'https://www.facebook.com/...',
            self::MODULE_FORMINPUTGROUP_CUP_TWITTER => 'https://twitter.com/...',
            self::MODULE_FORMINPUTGROUP_CUP_LINKEDIN => 'https://www.linkedin.com/...',
            self::MODULE_FORMINPUTGROUP_CUP_YOUTUBE => 'https://www.youtube.com/...',
            self::MODULE_FORMINPUTGROUP_CUP_INSTAGRAM => 'https://www.instagram.com/...',
        );
        if ($placeholder = $placeholders[$module[1]] ?? null) {
            $component = $this->getComponentSubmodule($module);
            $this->setProp($component, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($module, $props);
    }
}



