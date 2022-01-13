<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\PostTags\ComponentConfiguration as PostTagsComponentConfiguration;

class PoP_Module_Processor_TagTypeaheadComponentFormInputs extends PoP_Module_Processor_TagTypeaheadComponentFormInputsBase
{
    public const MODULE_TYPEAHEAD_COMPONENT_TAGS = 'forminput-typeaheadcomponent-tags';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_TAGS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return getRouteIcon(PostTagsComponentConfiguration::getPostTagsRoute(), true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(PostTagsComponentConfiguration::getPostTagsRoute());
        }

        return parent::getTypeaheadDataloadSource($module, $props);
    }
}



