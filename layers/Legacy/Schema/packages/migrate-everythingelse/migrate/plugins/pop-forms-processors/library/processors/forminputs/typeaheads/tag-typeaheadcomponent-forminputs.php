<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

class PoP_Module_Processor_TagTypeaheadComponentFormInputs extends PoP_Module_Processor_TagTypeaheadComponentFormInputsBase
{
    public final const MODULE_TYPEAHEAD_COMPONENT_TAGS = 'forminput-typeaheadcomponent-tags';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_TAGS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    protected function getTypeaheadDataloadSource(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(PostTagsModuleConfiguration::getPostTagsRoute());
        }

        return parent::getTypeaheadDataloadSource($componentVariation, $props);
    }
}



