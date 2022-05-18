<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;

// Add static suggestions: Search Content and Search Users
class GD_StaticSearchUtils
{
    public static function getContentSearchUrl($props, $query_wildcard = GD_JSPLACEHOLDER_QUERY)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $searchcontent_url = RouteUtils::getRouteURL(POP_BLOG_ROUTE_SEARCHCONTENT);
        $filter_params = array(
            [
                'component' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                'value' => $query_wildcard,
            ],
        );
        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams($searchcontent_url, $filter_params);
    }

    public static function getUsersSearchUrl($props, $query_wildcard = GD_JSPLACEHOLDER_QUERY)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $searchusers_url = RouteUtils::getRouteURL(POP_BLOG_ROUTE_SEARCHUSERS);
        $filter_params = array(
            [
                'component' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                'value' => $query_wildcard,
            ],
        );
        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams($searchusers_url, $filter_params);
    }
}
