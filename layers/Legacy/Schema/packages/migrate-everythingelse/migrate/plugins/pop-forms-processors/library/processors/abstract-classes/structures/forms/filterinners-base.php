<?php

abstract class PoP_Module_Processor_FilterInnersBase extends PoP_Module_Processor_FormInnersBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    // public function getFilterObject(array $module)
    // {
    //     if ($filter = $this->getFilter($module)) {
    //         return \PoP\Engine\FilterManagerFactory::getInstance()->getFilter($filter);
    //     }

    //     return null;
    // }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    protected function getInputSubmodules(array $module)
    {
        return [];
    }

    protected function getFilteredInputSubmodules(array $module)
    {
        return \PoP\Root\App::applyFilters(
            'FilterInnerComponentProcessor:inputmodules',
            $this->getInputSubmodules($module),
            $module
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        if ($input_modules = $this->getFilteredInputSubmodules($module)) {

            $ret = array_merge(
                $ret,
                $input_modules
            );

            // // Add the hidden input with the name of the filter
            // $ret[] = [self::class, self::MODULE_FORMINPUT_FILTERNAME];
        }

        if ($submitbtn = $this->getSubmitbtnModule($module)) {
            $ret[] = $submitbtn;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    // public function getFilterComponent(array $module, $filtercomponent)
    // {

    //     // By default get the forminput. This can be overriden to get the getFilterinput for a simpler filter, as used in the sideinfo.
    //     return $filtercomponent->getForminput();
    // }

    // public function getFilter(array $module)
    // {
    //     return null;
    // }

    public function getSubmitbtnModule(array $module)
    {
        return [PoP_Module_Processor_FormGroups::class, PoP_Module_Processor_FormGroups::MODULE_SUBMITBUTTONFORMGROUP_SEARCH];
    }

    // public function initModelProps(array $module, array &$props): void
    // {
    //     $this->setProp([[self::class, self::MODULE_FORMINPUT_FILTERNAME]], $props, 'filter', $this->getFilter($module));
    //     parent::initModelProps($module, $props);
    // }

    // function getModuleCbActions(array $module, array &$props) {

    //     // Comment Leo 23/08/2017: The filter must not be re-drawn after reloading/refreshing content,
    //     // it must not be affected by the data coming back from fetching json data, the filter is outside this scope
    //     return array(
    //         GD_MODULECALLBACK_ACTION_RESET,
    //     );
    // }
}
