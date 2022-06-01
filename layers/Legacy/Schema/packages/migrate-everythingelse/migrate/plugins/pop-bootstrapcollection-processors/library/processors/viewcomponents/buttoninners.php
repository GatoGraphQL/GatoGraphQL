<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA = 'viewcomponent-buttoninner-embed-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-embed-socialmedia-previewdropdown';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA = 'viewcomponent-buttoninner-api-socialmedia';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-api-socialmedia-previewdropdown';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA,
            self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN,
        );
    }
    
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
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

    public function getBtnTitle(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::COMPONENT_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($component);
    }
}


