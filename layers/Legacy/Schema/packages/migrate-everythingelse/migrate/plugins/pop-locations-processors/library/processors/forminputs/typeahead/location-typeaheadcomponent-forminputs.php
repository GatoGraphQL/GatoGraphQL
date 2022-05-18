<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_LocationTypeaheadComponentFormInputs extends PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase
{
    public final const MODULE_TYPEAHEAD_COMPONENT_LOCATIONS = 'forminput-typeaheadcomponent-locations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS],
        );
    }

    protected function getLimit(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS:
                return 8;
        }

        return parent::getLimit($module, $props);
    }

    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS:
                return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONS);
        }

        return parent::getTypeaheadDataloadSource($module, $props);
    }

    // protected function getSourceFilter(array $module, array &$props)
    // {
    //     return POP_FILTER_CONTENT;
    // }
    protected function getRemoteUrl(array $module, array &$props)
    {
        $url = parent::getRemoteUrl($module, $props);
        
        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams(
            $url, 
            [
                [
                    'module' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                    'value' => GD_JSPLACEHOLDER_QUERY,
                ],
            ]
        );
    }
}



