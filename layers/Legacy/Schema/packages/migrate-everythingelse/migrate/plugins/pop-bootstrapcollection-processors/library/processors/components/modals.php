<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
class PoP_Module_Processor_ShareModalComponents extends PoP_Module_Processor_FormModalViewComponentsBase
{
    public final const MODULE_MODAL_EMBED = 'modal-embed';
    public final const MODULE_MODAL_API = 'modal-api';
    public final const MODULE_MODAL_COPYSEARCHURL = 'modal-copysearchurl';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MODAL_EMBED],
            [self::class, self::MODULE_MODAL_API],
            [self::class, self::MODULE_MODAL_COPYSEARCHURL],
        );
    }
    
    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_EMBED:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::MODULE_MULTIPLE_EMBED];
                break;

            case self::MODULE_MODAL_API:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::MODULE_MULTIPLE_API];
                break;

            case self::MODULE_MODAL_COPYSEARCHURL:
                $ret[] = [PoP_Module_Processor_ShareMultiples::class, PoP_Module_Processor_ShareMultiples::MODULE_MULTIPLE_COPYSEARCHURL];
                break;
        }

        return $ret;
    }

    public function getHeaderTitle(array $componentVariation)
    {
        $header_placeholder = '<i class="fa %s fa-fw"></i><em>%s</em>';
        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_EMBED:
                return sprintf(
                    $header_placeholder,
                    'fa-code',
                    TranslationAPIFacade::getInstance()->__('Embed:', 'pop-coreprocessors')
                );

            case self::MODULE_MODAL_API:
                return sprintf(
                    $header_placeholder,
                    'fa-cog',
                    TranslationAPIFacade::getInstance()->__('API Data:', 'pop-coreprocessors')
                );

            case self::MODULE_MODAL_COPYSEARCHURL:
                return sprintf(
                    $header_placeholder,
                    'fa-link',
                    TranslationAPIFacade::getInstance()->__('Copy Search URL:', 'pop-coreprocessors')
                );
        }

        return parent::getHeaderTitle($componentVariation);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_EMBED:
            case self::MODULE_MODAL_API:
                $urlTypes = array(
                    self::MODULE_MODAL_EMBED => 'embed',
                    self::MODULE_MODAL_API => 'api',
                );

                // Since we're in a modal, make the embedPreview get reloaded when opening the modal
                $this->mergePagesectionJsmethodProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW], $props, array('modalReloadEmbedPreview'));
                $this->mergeImmutableJsconfigurationProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW], $props, array('modalReloadEmbedPreview' => array('url-type' => $urlTypes[$componentVariation[1]])));
                // $this->setProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW], $props, 'module-cb', true);
                $this->appendProp([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW], $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass([PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_EMBEDPREVIEW]));
                break;
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
}


