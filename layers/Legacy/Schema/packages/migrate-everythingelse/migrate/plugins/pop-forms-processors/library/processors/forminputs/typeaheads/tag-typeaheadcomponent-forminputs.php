<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class PoP_Module_Processor_TagTypeaheadComponentFormInputs extends PoP_Module_Processor_TagTypeaheadComponentFormInputsBase
{
    public final const COMPONENT_TYPEAHEAD_COMPONENT_TAGS = 'forminput-typeaheadcomponent-tags';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS:
                return getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getTypeaheadDataloadSource(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(PostTagsModuleConfiguration::getPostTagsRoute());
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }
}



