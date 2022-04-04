<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostHeaderViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA = 'viewcomponent-postbutton-embed-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN = 'viewcomponent-postbutton-embed-previewdropdown';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA = 'viewcomponent-postbutton-api-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN = 'viewcomponent-postbutton-api-previewdropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-embed';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-api';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }

        return parent::getTitle($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
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
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
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
                case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
                case self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
                case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
                case self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
                    $modals = array(
                        self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                        self::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                    );

                    $modal = $modals[$module[1]];
                    $modal_id = $moduleprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props);
                    return '#'.PoP_Bootstrap_Utils::getFrontendId($modal_id, 'modal');
            }
        }

        return parent::getUrl($module, $props);
    }
}



