<?php

abstract class PoP_Module_Processor_SidebarInnersBase extends PoP_Module_Processor_ContentSingleInnersBase
{
    public function getWrapperClass(array $componentVariation)
    {
        return '';
    }
    public function getWidgetwrapperClass(array $componentVariation)
    {
        return '';
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($wrapper_class = $this->getWrapperClass($componentVariation)) {
            $this->appendProp($componentVariation, $props, 'class', $wrapper_class);
        }
        if ($widgetwrapper_class = $this->getWidgetwrapperClass($componentVariation)) {
            foreach ($this->getLayoutSubmodules($componentVariation) as $layout) {
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

        parent::initModelProps($componentVariation, $props);
    }
}
