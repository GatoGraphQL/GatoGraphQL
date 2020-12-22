<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\Route\RouteUtils;

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
                return getRouteIcon(POP_POSTTAGS_ROUTE_POSTTAGS, true).TranslationAPIFacade::getInstance()->__('Tags:', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_TAGS:
                return RouteUtils::getRouteURL(POP_POSTTAGS_ROUTE_POSTTAGS);
        }

        return parent::getTypeaheadDataloadSource($module, $props);
    }
}



