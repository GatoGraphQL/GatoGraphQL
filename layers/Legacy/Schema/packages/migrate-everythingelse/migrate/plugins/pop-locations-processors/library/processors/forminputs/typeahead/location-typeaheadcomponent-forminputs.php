<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_LocationTypeaheadComponentFormInputs extends PoP_Module_Processor_LocationTypeaheadComponentFormInputsBase
{
    public final const MODULE_TYPEAHEAD_COMPONENT_LOCATIONS = 'forminput-typeaheadcomponent-locations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TYPEAHEAD_COMPONENT_LOCATIONS],
        );
    }

    protected function getLimit(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_LOCATIONS:
                return 8;
        }

        return parent::getLimit($component, $props);
    }

    protected function getTypeaheadDataloadSource(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_LOCATIONS:
                return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONS);
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }

    // protected function getSourceFilter(array $component, array &$props)
    // {
    //     return POP_FILTER_CONTENT;
    // }
    protected function getRemoteUrl(array $component, array &$props)
    {
        $url = parent::getRemoteUrl($component, $props);
        
        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams(
            $url, 
            [
                [
                    'component' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                    'value' => GD_JSPLACEHOLDER_QUERY,
                ],
            ]
        );
    }
}



