<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA = 'viewcomponent-buttoninner-embed-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-embed-socialmedia-previewdropdown';
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA = 'viewcomponent-buttoninner-api-socialmedia';
    public final const MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-api-socialmedia-previewdropdown';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN],
        );
    }
    
    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA:
                return 'fa-fw fa-code fa-lg';

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:
                return 'fa-fw fa-code';

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA:
                return 'fa-fw fa-cog fa-lg';

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN:
                return 'fa-fw fa-cog';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


