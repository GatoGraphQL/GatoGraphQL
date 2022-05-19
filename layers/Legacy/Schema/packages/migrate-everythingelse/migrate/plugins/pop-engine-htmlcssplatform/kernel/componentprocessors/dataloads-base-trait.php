<?php
define('POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER', 'hook-dataloadingsbase-filteringbyshowfilter');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;

trait PoPHTMLCSSPlatform_Processor_DataloadsBaseTrait
{
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($filter_component = $this->getFilterSubcomponent($component)) {
            if ($this->getProp($component, $props, 'show-filter')) {
                $ret['show-filter'] = true;
            }
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['filter'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($filter_component);
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {

            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

            $this->setProp($component, $props, 'show-filter', true);

            if ($filter_component = $this->getFilterSubcomponent($component)) {

                // Class needed for the proxyForm's selector when proxying this one block
                $this->appendProp($component, $props, 'class', 'withfilter');

                // Filter hidden: always hide it, eg: for Full Post
                if ($show_filter = $this->getProp($component, $props, 'show-filter')) {

                    // "pop-blockfilter": Class needed for the proxyForm's selector when proxying this one form
                    $class = 'pop-blockfilter collapse alert alert-info form-horizontal';

                    if ($this->getProp($component, $props, 'filter-hidden')) {
                        $class .= ' hidden';
                    }

                    $this->appendProp($filter_component, $props, 'class', $class);
                }
            }

            $this->metaInitProps($component, $props);
        }
        parent::initModelProps($component, $props);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {

            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

            if ($filter_component = $this->getFilterSubcomponent($component)) {

                // Filter visible: if explicitly defined, or if currently filtering with it
                if ($show_filter = $this->getProp($component, $props, 'show-filter')) {

                    // Comment Leo 31/10/2014: don't show the filter open when filtering by anymore for MESYM v4,
                    // it takes so much space specially in the embed, and in some case, eg:
                    // http://m3l.localhost/calendar/?calendaryear=2014&calendarmonth=7&searchfor&filter=events-calendar
                    // it doesn't even show any param being filtered (month or year not chosen in filter)
                    // Comment Leo 15/04/2015: do not show the filter even if filtering for EMBED and PRINT
                    if (\PoP\Root\App::applyFilters(POP_HOOK_DATALOADINGSBASE_FILTERINGBYSHOWFILTER, true)) {
                        // if ($filter = $componentprocessor_manager->getProcessor($filter_component)->getFilter($filter_component)) {
                        $filterVisible = $this->getProp($component, $props, 'filter-visible');
                        // if ($filterVisible || \PoP\Engine\FilterUtils::filteringBy($filter)) {
                        if ($filterVisible || $this->getActiveDataloadQueryArgsFilteringComponents($component)) {

                            // Filter will be open depending on URL params, so make this class a runtime one
                            $this->appendProp($filter_component, $props, 'runtime-class', 'in');
                        }
                        // }
                    }
                }
            }
        }

        parent::initRequestProps($component, $props);
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function filterVisible(array $component)
    {
        return false;
    }
}
