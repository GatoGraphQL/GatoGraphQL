<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TagViewComponentButtons extends PoP_Module_Processor_TagViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA = 'viewcomponent-tagbutton-embed-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN = 'viewcomponent-tagbutton-embed-previewdropdown';
    public final const MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA = 'viewcomponent-tagbutton-api-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN = 'viewcomponent-tagbutton-api-previewdropdown';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN],
        );
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN],
            self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA],
            self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-embed';
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-api';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'modal'
                    )
                );
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
            case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getUrl(array $componentVariation, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            switch ($componentVariation[1]) {
                case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA:
                case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN:
                case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA:
                case self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN:
                    $modals = array(
                        self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_EMBED],
                        self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                        self::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::MODULE_MODAL_API],
                    );

                    $modal = $modals[$componentVariation[1]];
                    $modal_id = $componentprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props);
                    return '#'.PoP_Bootstrap_Utils::getFrontendId($modal_id, 'modal');
            }
        }

        return parent::getUrl($componentVariation, $props);
    }
}



