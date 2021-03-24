<?php

abstract class PoP_Module_Processor_SidebarInnersBase extends PoP_Module_Processor_ContentSingleInnersBase
{
    public function getWrapperClass(array $module)
    {
        return '';
    }
    public function getWidgetwrapperClass(array $module)
    {
        return '';
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($wrapper_class = $this->getWrapperClass($module)) {
            $this->appendProp($module, $props, 'class', $wrapper_class);
        }
        if ($widgetwrapper_class = $this->getWidgetwrapperClass($module)) {
            foreach ($this->getLayoutSubmodules($module) as $layout) {
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

        parent::initModelProps($module, $props);
    }
}
