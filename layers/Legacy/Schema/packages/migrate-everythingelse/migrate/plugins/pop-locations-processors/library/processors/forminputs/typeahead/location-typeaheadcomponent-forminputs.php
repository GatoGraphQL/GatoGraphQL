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

    protected function getLimit(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS:
                return 8;
        }

        return parent::getLimit($componentVariation, $props);
    }

    protected function getTypeaheadDataloadSource(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_LOCATIONS:
                return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONS);
        }

        return parent::getTypeaheadDataloadSource($componentVariation, $props);
    }

    // protected function getSourceFilter(array $componentVariation, array &$props)
    // {
    //     return POP_FILTER_CONTENT;
    // }
    protected function getRemoteUrl(array $componentVariation, array &$props)
    {
        $url = parent::getRemoteUrl($componentVariation, $props);
        
        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams(
            $url, 
            [
                [
                    'component-variation' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                    'value' => GD_JSPLACEHOLDER_QUERY,
                ],
            ]
        );
    }
}



