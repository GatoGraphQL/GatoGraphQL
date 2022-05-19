<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostTypeaheadComponentFormInputs extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    public final const COMPONENT_TYPEAHEAD_COMPONENT_CONTENT = 'forminput-typeaheadcomponent-content';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT:
                return getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Content:', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getTypeaheadDataloadSource(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT:
                return RouteUtils::getRouteURL(POP_BLOG_ROUTE_CONTENT);
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }


    // protected function getSourceFilter(array $component, array &$props)
    // {
    //     return POP_FILTER_CONTENT;
    // }
    protected function getSourceFilterParams(array $component, array &$props)
    {
        $ret = parent::getSourceFilterParams($component, $props);

        // bring the posts ordering by comment count
        if (defined('POP_COMMENTS_INITIALIZED')) {
            $ret[] = [
                'component' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
                'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:comment-count').'|DESC',
            ];
        }

        return $ret;
    }
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



