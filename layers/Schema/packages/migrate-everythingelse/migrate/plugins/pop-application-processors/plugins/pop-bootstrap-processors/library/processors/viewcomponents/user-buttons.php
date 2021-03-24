<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons extends PoP_Module_Processor_UserHeaderViewComponentButtonsBase
{
    public const MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA = 'viewcomponent-userbutton-sharebyemail-socialmedia';
    public const MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN = 'viewcomponent-userbutton-sharebyemail-previewdropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA => [PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners::class, PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN => [PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners::class, PoP_GenericForms_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_SHAREBYEMAIL_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Share by email', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-email';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'modal'
                    )
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getUrl(array $module, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            switch ($module[1]) {
                case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA:
                case self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN:
                    $modals = array(
                        self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA => [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::MODULE_MODAL_SHAREBYEMAIL],
                        self::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN => [PoP_Module_Processor_GFModalComponents::class, PoP_Module_Processor_GFModalComponents::MODULE_MODAL_SHAREBYEMAIL],
                    );

                    $modal = $modals[$module[1]];
                    $modal_id = $moduleprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props);
                    return '#'.PoP_Bootstrap_Utils::getFrontendId($modal_id, 'modal');
            }
        }

        return parent::getUrl($module, $props);
    }
}



