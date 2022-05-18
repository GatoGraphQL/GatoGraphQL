<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_PostTypeaheadComponentFormInputs extends PoP_Module_Processor_PostTypeaheadComponentFormInputsBase
{
    public final const MODULE_TYPEAHEAD_COMPONENT_CONTENT = 'forminput-typeaheadcomponent-content';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_CONTENT],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_CONTENT:
                return getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Content:', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    protected function getTypeaheadDataloadSource(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_CONTENT:
                return RouteUtils::getRouteURL(POP_BLOG_ROUTE_CONTENT);
        }

        return parent::getTypeaheadDataloadSource($componentVariation, $props);
    }


    // protected function getSourceFilter(array $componentVariation, array &$props)
    // {
    //     return POP_FILTER_CONTENT;
    // }
    protected function getSourceFilterParams(array $componentVariation, array &$props)
    {
        $ret = parent::getSourceFilterParams($componentVariation, $props);

        // bring the posts ordering by comment count
        if (defined('POP_COMMENTS_INITIALIZED')) {
            $ret[] = [
                'component-variation' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
                'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:comment-count').'|DESC',
            ];
        }

        return $ret;
    }
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



