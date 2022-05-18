<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostViewComponentButtons extends PoP_Module_Processor_PostHeaderViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA = 'viewcomponent-postbutton-embed-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN = 'viewcomponent-postbutton-embed-previewdropdown';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA = 'viewcomponent-postbutton-api-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN = 'viewcomponent-postbutton-api-previewdropdown';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN],
        );
    }

    public function getButtoninnerSubmodule(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => [GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::class, GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($component);
    }

    public function getBtnClass(array $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-embed';
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
                $ret .= ' socialmedia-item socialmedia-api';
                break;
        }

        return $ret;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }

        return parent::getTitle($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-toggle' => 'modal'
                    )
                );
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'socialmedia');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getUrl(array $component, array &$props)
    {

        // If PoP Engine Web Platform is not defined, then there is no `getFrontendId`
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            switch ($component[1]) {
                case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA:
                case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN:
                case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA:
                case self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN:
                    $modals = array(
                        self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::COMPONENT_MODAL_EMBED],
                        self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::COMPONENT_MODAL_EMBED],
                        self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::COMPONENT_MODAL_API],
                        self::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN => [PoP_Module_Processor_ShareModalComponents::class, PoP_Module_Processor_ShareModalComponents::COMPONENT_MODAL_API],
                    );

                    $modal = $modals[$component[1]];
                    $modal_id = $componentprocessor_manager->getProcessor($modal)->getFrontendId($modal, $props);
                    return '#'.PoP_Bootstrap_Utils::getFrontendId($modal_id, 'modal');
            }
        }

        return parent::getUrl($component, $props);
    }
}



