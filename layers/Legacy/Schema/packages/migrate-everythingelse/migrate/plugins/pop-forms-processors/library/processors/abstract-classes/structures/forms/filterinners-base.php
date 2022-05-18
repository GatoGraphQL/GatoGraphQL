<?php

abstract class PoP_Module_Processor_FilterInnersBase extends PoP_Module_Processor_FormInnersBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    // public function getFilterObject(array $component)
    // {
    //     if ($filter = $this->getFilter($component)) {
    //         return \PoP\Engine\FilterManagerFactory::getInstance()->getFilter($filter);
    //     }

    //     return null;
    // }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    protected function getInputSubmodules(array $component)
    {
        return [];
    }

    protected function getFilteredInputSubmodules(array $component)
    {
        return \PoP\Root\App::applyFilters(
            'FilterInnerComponentProcessor:inputmodules',
            $this->getInputSubmodules($component),
            $component
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        if ($input_components = $this->getFilteredInputSubmodules($component)) {

            $ret = array_merge(
                $ret,
                $input_components
            );

            // // Add the hidden input with the name of the filter
            // $ret[] = [self::class, self::COMPONENT_FORMINPUT_FILTERNAME];
        }

        if ($submitbtn = $this->getSubmitbtnModule($component)) {
            $ret[] = $submitbtn;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    // public function getFilterComponent(array $component, $filtercomponent)
    // {

    //     // By default get the forminput. This can be overriden to get the getFilterinput for a simpler filter, as used in the sideinfo.
    //     return $filtercomponent->getForminput();
    // }

    // public function getFilter(array $component)
    // {
    //     return null;
    // }

    public function getSubmitbtnModule(array $component)
    {
        return [PoP_Module_Processor_FormGroups::class, PoP_Module_Processor_FormGroups::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH];
    }

    // public function initModelProps(array $component, array &$props): void
    // {
    //     $this->setProp([[self::class, self::COMPONENT_FORMINPUT_FILTERNAME]], $props, 'filter', $this->getFilter($component));
    //     parent::initModelProps($component, $props);
    // }

    // function getModuleCbActions(array $component, array &$props) {

    //     // Comment Leo 23/08/2017: The filter must not be re-drawn after reloading/refreshing content,
    //     // it must not be affected by the data coming back from fetching json data, the filter is outside this scope
    //     return array(
    //         GD_MODULECALLBACK_ACTION_RESET,
    //     );
    // }
}
