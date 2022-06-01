<?php

abstract class PoP_Module_Processor_FilterInnersBase extends PoP_Module_Processor_FormInnersBase
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    // public function getFilterObject(\PoP\ComponentModel\Component\Component $component)
    // {
    //     if ($filter = $this->getFilter($component)) {
    //         return \PoP\Engine\FilterManagerFactory::getInstance()->getFilter($filter);
    //     }

    //     return null;
    // }

    //-------------------------------------------------
    // PUBLIC Overriding Functions
    //-------------------------------------------------

    protected function getInputSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return [];
    }

    protected function getFilteredInputSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return \PoP\Root\App::applyFilters(
            'FilterInnerComponentProcessor:inputComponents',
            $this->getInputSubcomponents($component),
            $component
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        if ($input_components = $this->getFilteredInputSubcomponents($component)) {

            $ret = array_merge(
                $ret,
                $input_components
            );

            // // Add the hidden input with the name of the filter
            // $ret[] = self::COMPONENT_FORMINPUT_FILTERNAME;
        }

        if ($submitbtn = $this->getSubmitbtnComponent($component)) {
            $ret[] = $submitbtn;
        }

        return $ret;
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    // public function getFilterComponent(\PoP\ComponentModel\Component\Component $component, $filtercomponent)
    // {

    //     // By default get the forminput. This can be overriden to get the getFilterinput for a simpler filter, as used in the sideinfo.
    //     return $filtercomponent->getForminput();
    // }

    // public function getFilter(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return null;
    // }

    public function getSubmitbtnComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_FormGroups::class, PoP_Module_Processor_FormGroups::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH];
    }

    // public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    // {
    //     $this->setProp([self::COMPONENT_FORMINPUT_FILTERNAME], $props, 'filter', $this->getFilter($component));
    //     parent::initModelProps($component, $props);
    // }

    // function getComponentCBActions(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     // Comment Leo 23/08/2017: The filter must not be re-drawn after reloading/refreshing content,
    //     // it must not be affected by the data coming back from fetching json data, the filter is outside this scope
    //     return array(
    //         GD_COMPONENTCALLBACK_ACTION_RESET,
    //     );
    // }
}
