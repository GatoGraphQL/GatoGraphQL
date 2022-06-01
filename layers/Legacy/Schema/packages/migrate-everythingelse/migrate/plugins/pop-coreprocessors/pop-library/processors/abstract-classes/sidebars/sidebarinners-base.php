<?php

abstract class PoP_Module_Processor_SidebarInnersBase extends PoP_Module_Processor_ContentSingleInnersBase
{
    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($wrapper_class = $this->getWrapperClass($component)) {
            $this->appendProp($component, $props, 'class', $wrapper_class);
        }
        if ($widgetwrapper_class = $this->getWidgetwrapperClass($component)) {
            foreach ($this->getLayoutSubcomponents($component) as $layout) {
                $this->mergeProp(
                    $layout,
                    $props,
                    'classes',
                    array(
                        $widgetwrapper_class
                    )
                );
            }
        }

        parent::initModelProps($component, $props);
    }
}
