<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Core_Bootstrap_Module_Processor_ViewComponentButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA = 'viewcomponent-buttoninner-embed-socialmedia';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-embed-socialmedia-previewdropdown';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA = 'viewcomponent-buttoninner-api-socialmedia';
    public const MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN = 'viewcomponent-buttoninner-api-socialmedia-previewdropdown';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN],
        );
    }
    
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_SOCIALMEDIA:
                return 'fa-fw fa-code fa-lg';

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:
                return 'fa-fw fa-code';

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_API_SOCIALMEDIA:
                return 'fa-fw fa-cog fa-lg';

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN:
                return 'fa-fw fa-cog';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getBtnTitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_EMBED_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('Embed', 'pop-coreprocessors');

            case self::MODULE_VIEWCOMPONENT_BUTTONINNER_API_PREVIEWDROPDOWN:
                return TranslationAPIFacade::getInstance()->__('API Data', 'pop-coreprocessors');
        }
        
        return parent::getBtnTitle($module);
    }
}


