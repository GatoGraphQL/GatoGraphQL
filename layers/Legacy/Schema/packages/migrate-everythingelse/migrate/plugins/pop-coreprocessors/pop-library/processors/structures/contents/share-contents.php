<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_ShareContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENT_EMBEDPREVIEW = 'content-embedpreview';
    public final const COMPONENT_CONTENT_EMBED = 'content-embed';
    public final const COMPONENT_CONTENT_API = 'content-api';
    public final const COMPONENT_CONTENT_COPYSEARCHURL = 'content-copysearchurl';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENT_EMBEDPREVIEW,
            self::COMPONENT_CONTENT_EMBED,
            self::COMPONENT_CONTENT_API,
            self::COMPONENT_CONTENT_COPYSEARCHURL,
        );
    }

    protected function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CONTENT_EMBEDPREVIEW:
                return sprintf(
                    '<h4><i class="fa fa-fw fa-eye"></i>%s</h4>',
                    TranslationAPIFacade::getInstance()->__('Preview:', 'pop-coreprocessors')
                );

            case self::COMPONENT_CONTENT_EMBED:
                return sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Please copy/paste the html code below into your website.', 'pop-coreprocessors')
                );

            case self::COMPONENT_CONTENT_COPYSEARCHURL:
            case self::COMPONENT_CONTENT_API:
                return sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Please select and copy (Ctrl + C) the URL below.', 'pop-coreprocessors')
                );
        }

        return parent::getDescription($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CONTENT_EMBEDPREVIEW:
                return [PoP_Module_Processor_ShareContentInners::class, PoP_Module_Processor_ShareContentInners::COMPONENT_CONTENTINNER_EMBEDPREVIEW];

            case self::COMPONENT_CONTENT_EMBED:
                return [PoP_Module_Processor_ShareContentInners::class, PoP_Module_Processor_ShareContentInners::COMPONENT_CONTENTINNER_EMBED];

            case self::COMPONENT_CONTENT_API:
                return [PoP_Module_Processor_ShareContentInners::class, PoP_Module_Processor_ShareContentInners::COMPONENT_CONTENTINNER_API];

            case self::COMPONENT_CONTENT_COPYSEARCHURL:
                return [PoP_Module_Processor_ShareContentInners::class, PoP_Module_Processor_ShareContentInners::COMPONENT_CONTENTINNER_COPYSEARCHURL];
        }

        return parent::getInnerSubcomponent($component);
    }
}


