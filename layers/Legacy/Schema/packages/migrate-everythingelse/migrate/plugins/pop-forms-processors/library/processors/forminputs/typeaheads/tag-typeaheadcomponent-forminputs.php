<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class PoP_Module_Processor_TagTypeaheadComponentFormInputs extends PoP_Module_Processor_TagTypeaheadComponentFormInputsBase
{
    public final const COMPONENT_TYPEAHEAD_COMPONENT_TAGS = 'forminput-typeaheadcomponent-tags';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS:
                return getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getTypeaheadDataloadSource(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(PostTagsModuleConfiguration::getPostTagsRoute());
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }
}



