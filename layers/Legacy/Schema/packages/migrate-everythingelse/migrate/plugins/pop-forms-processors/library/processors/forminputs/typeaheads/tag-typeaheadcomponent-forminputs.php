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

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return getRouteIcon(PostTagsModuleConfiguration::getPostTagsRoute(), true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(PostTagsModuleConfiguration::getPostTagsRoute());
        }

        return parent::getTypeaheadDataloadSource($module, $props);
    }
}



