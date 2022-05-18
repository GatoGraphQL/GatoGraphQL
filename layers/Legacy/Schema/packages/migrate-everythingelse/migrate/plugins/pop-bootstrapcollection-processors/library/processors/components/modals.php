<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
class PoP_Module_Processor_ShareModalComponents extends PoP_Module_Processor_FormModalViewComponentsBase
{
    public final const COMPONENT_MODAL_EMBED = 'modal-embed';
    public final const COMPONENT_MODAL_API = 'modal-api';
    public final const COMPONENT_MODAL_COPYSEARCHURL = 'modal-copysearchurl';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MODAL_EMBED],
            [self::class, self::COMPONENT_MODAL_API],
            [self::class, self::COMPONENT_MODAL_COPYSEARCHURL],
        );
    }
    
    public function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_MODAL_EMBED:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::COMPONENT_MULTIPLE_EMBED];
                break;

            case self::COMPONENT_MODAL_API:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::COMPONENT_MULTIPLE_API];
                break;

            case self::COMPONENT_MODAL_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::COMPONENT_MULTIPLE_COPYSEARCHURL];
                break;
        }

        return $ret;
    }

    public function getHeaderTitle(array $component)
    {
        $header_placeholder = '<i class="fa %s fa-fw"></i><em>%s</em>';
        switch ($component[1]) {
            case self::COMPONENT_MODAL_EMBED:
                return sprintf(
                    $header_placeholder,
                    'fa-code',
                    TranslationAPIFacade::getInstance()->__('Embed:', 'pop-coreprocessors')
                );

            case self::COMPONENT_MODAL_API:
                return sprintf(
                    $header_placeholder,
                    'fa-cog',
                    TranslationAPIFacade::getInstance()->__('API Data:', 'pop-coreprocessors')
                );

            case self::COMPONENT_MODAL_COPYSEARCHURL:
                return sprintf(
                    $header_placeholder,
                    'fa-link',
                    TranslationAPIFacade::getInstance()->__('Copy Search URL:', 'pop-coreprocessors')
                );
        }

        return parent::getHeaderTitle($component);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_MODAL_EMBED:
            case self::COMPONENT_MODAL_API:
                $urlTypes = array(
                    self::COMPONENT_MODAL_EMBED => 'embed',
                    self::COMPONENT_MODAL_API => 'api',
                );

                // Since we're in a modal, make the embedPreview get reloaded when opening the modal
                $this->mergePagesectionJsmethodProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW], $props, array('modalReloadEmbedPreview'));
                $this->mergeImmutableJsconfigurationProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW], $props, array('modalReloadEmbedPreview' => array('url-type' => $urlTypes[$component[1]])));
                // $this->setProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW], $props, 'component-cb', true);
                $this->appendProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW], $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_EMBEDPREVIEW]));
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}


