<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

abstract class PoP_Module_Processor_TypeaheadComponentFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    private $resources;

    public function __construct()
    {
        // Comment Leo 20/10/2017: Important! Because the module will be rendered on runtime in the front-end,
        // we must make sure that this module is delivered on the ResourceLoader when doing code-splitting
        if (defined('POP_RESOURCELOADER_INITIALIZED')) {
            $this->resources = array();
            HooksAPIFacade::getInstance()->addFilter(
                'PoP_CoreProcessors_ResourceLoaderProcessor:typeahead:templates',
                array($this, 'getDependencies')
            );
        }
    }

    public function getSubmodules(array $module): array
    {

        // If PoP Resource Loader is not defined, then there is no PoP_ResourceLoaderProcessorUtils
        if (defined('POP_RESOURCELOADER_INITIALIZED')) {
            // Comment Leo 23/01/2018: if we are executing `getSubmodules($module)` then this module is included in the output,
            // then we can already add its dependencies
            // In the past, this was done in `get_js_setting`, but this doesn't work consistently between generating bundlegroups pregenerated or in runtime,
            // since that function may or may not be executed. But calling `getSubmodules` will always work
            // Comment Leo 20/10/2017: Important! Because the module will be rendered on runtime in the front-end,
            // we must make sure that this module is delivered on the ResourceLoader when doing code-splitting
            $this->resources[] = $this->getComponentTemplateResource($module);
        }

        return parent::getSubmodules($module);
    }

    public function getDependencies($resources)
    {
        if ($this->resources) {
            $resources = array_merge(
                $resources,
                array_unique(
                    $this->resources,
                    SORT_REGULAR
                )
            );
        }
        return $resources;
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return $this->getComponentTemplateResource($module);
    }
    protected function getComponentTemplateResource(array $module)
    {
        return null;
    }

    protected function getValueKey(array $module, array &$props)
    {
        return 'id';
    }
    protected function getTokenizerKeys(array $module, array &$props)
    {
        return array();
    }
    protected function getLimit(array $module, array &$props)
    {
        return 5;
    }

    protected function getThumbprintQuery(array $module, array &$props)
    {
        return array();
    }
    protected function executeThumbprint($query)
    {
        return array();
    }
    protected function getThumbprint(array $module, array &$props)
    {
        $query = $this->getThumbprintQuery($module, $props);
        $results = $this->executeThumbprint($query);

        return (int) $results[0];
    }
    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        return null;
    }
    // protected function getSourceFilter(array $module, array &$props)
    // {
    //     return null;
    // }
    protected function getSourceFilterParams(array $module, array &$props)
    {
        return [];
    }
    protected function getSourceUrl(array $module, array &$props)
    {
        $url = $this->getTypeaheadDataloadSource($module, $props);

        // Add the output=json params, typeahead datastructure
        $url = PoPCore_ModuleManager_Utils::addJsonoutputResultsParams($url, POP_FORMAT_TYPEAHEAD);

        if ($filter_params = $this->getSourceFilterParams($module, $props)) {
            $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
            $url = $dataloadHelperService->addFilterParams($url, $filter_params);
        }

        return $url;
    }
    protected function getPrefetchUrl(array $module, array &$props)
    {
        $url = $this->getSourceUrl($module, $props);

        // Bring 10 times the pre-defined result set
        $cmsService = CMSServiceFacade::getInstance();
        $limit = $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:limit')) * 10;
        return GeneralUtils::addQueryArgs([
            \PoP\ComponentModel\Constants\PaginationParams::LIMIT => $limit,
        ], $url);
    }
    protected function getRemoteUrl(array $module, array &$props)
    {
        $url = $this->getSourceUrl($module, $props);
        return GeneralUtils::addQueryArgs([
            \PoP\ComponentModel\Constants\PaginationParams::LIMIT => 12,
        ], $url);
    }
    protected function getStaticSuggestions(array $module, array &$props)
    {
        return array();
    }
    protected function getPendingMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Suggestions', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('No Results', 'pop-coreprocessors');
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        $label = $this->getLabel($module, $props);

        // Dataset
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $this->getComponentTemplateResource($module);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        $template = $resourceprocessor->getTemplate($templateResource);
        $ret['typeahead']['dataset'] = array(
            'template' => $template,
            'name' => $module,
            'header' => $label ? '<strong class="menu-text">'.$label.'</strong>' : '',
            'pending' => '<p class="clearfix menu-text text-warning"><em>'.GD_CONSTANT_LOADING_SPINNER.' '.$this->getPendingMsg($module).'</em></p>',
            'notFound' => '<p class="clearfix menu-text"><em>'.$this->getNotfoundMsg($module).'</em></p>',
            'valueKey' => $this->getValueKey($module, $props),
            'limit' => $this->getLimit($module, $props),
            'tokenizerKeys' => $this->getTokenizerKeys($module, $props),
        );

        // Static suggestions: no need for remote/prefetch
        if ($staticSuggestions = $this->getStaticSuggestions($module, $props)) {
            $ret['typeahead']['dataset']['staticSuggestions'] = $staticSuggestions;
            $ret['typeahead']['dataset']['local'] = array(); // local attribute is mandatory if no remove/prefetch provided
        } else {
            $ret['typeahead']['dataset']['remote'] = $this->getRemoteUrl($module, $props);
            $ret['typeahead']['dataset']['prefetch'] = $this->getPrefetchUrl($module, $props);
        }

        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($module, $props);

        // Comment Leo 10/08/2017: the thumbprint value will make the ETag for a page to change whenever there is a new post,
        // even if this new post is show in that page
        // Then, this 'thumbprint' key+value will need to be removed before doing wp_hash
        if ($thumbprint = $this->getThumbprint($module, $props)) {
            $ret['typeahead']['dataset'] = array(
                POP_KEYS_THUMBPRINT => $thumbprint,
            );
        }

        return $ret;
    }
}
