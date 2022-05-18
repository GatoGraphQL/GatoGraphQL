<?php

abstract class PoP_Module_Processor_FilterInnersBase extends PoP_Module_Processor_FormInnersBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    // public function getFilterObject(array $componentVariation)
    // {
    //     if ($filter = $this->getFilter($componentVariation)) {
    //         return \PoP\Engine\FilterManagerFactory::getInstance()->getFilter($filter);
    //     }

    //     return null;
    // }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    protected function getInputSubmodules(array $componentVariation)
    {
        return [];
    }

    protected function getFilteredInputSubmodules(array $componentVariation)
    {
        return \PoP\Root\App::applyFilters(
            'FilterInnerComponentProcessor:inputmodules',
            $this->getInputSubmodules($componentVariation),
            $componentVariation
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        if ($input_modules = $this->getFilteredInputSubmodules($componentVariation)) {

            $ret = array_merge(
                $ret,
                $input_modules
            );

            // // Add the hidden input with the name of the filter
            // $ret[] = [self::class, self::MODULE_FORMINPUT_FILTERNAME];
        }

        if ($submitbtn = $this->getSubmitbtnModule($componentVariation)) {
            $ret[] = $submitbtn;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    // public function getFilterComponent(array $componentVariation, $filtercomponent)
    // {

    //     // By default get the forminput. This can be overriden to get the getFilterinput for a simpler filter, as used in the sideinfo.
    //     return $filtercomponent->getForminput();
    // }

    // public function getFilter(array $componentVariation)
    // {
    //     return null;
    // }

    public function getSubmitbtnModule(array $componentVariation)
    {
        return [PoP_Module_Processor_FormGroups::class, PoP_Module_Processor_FormGroups::MODULE_SUBMITBUTTONFORMGROUP_SEARCH];
    }

    // public function initModelProps(array $componentVariation, array &$props): void
    // {
    //     $this->setProp([[self::class, self::MODULE_FORMINPUT_FILTERNAME]], $props, 'filter', $this->getFilter($componentVariation));
    //     parent::initModelProps($componentVariation, $props);
    // }

    // function getModuleCbActions(array $componentVariation, array &$props) {

    //     // Comment Leo 23/08/2017: The filter must not be re-drawn after reloading/refreshing content,
    //     // it must not be affected by the data coming back from fetching json data, the filter is outside this scope
    //     return array(
    //         GD_MODULECALLBACK_ACTION_RESET,
    //     );
    // }
}
