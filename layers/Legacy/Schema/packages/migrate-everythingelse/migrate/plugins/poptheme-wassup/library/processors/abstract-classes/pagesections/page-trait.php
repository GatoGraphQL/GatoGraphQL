<?php

trait PoPTheme_Wassup_Module_Processor_PageTrait
{
    protected function getFrameoptionsSubmodules(array $module): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($module),
            $this->getFramebottomoptionsSubmodules($module)
        );
    }

    public function getFrametopoptionsSubmodules(array $module): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $module): array
    {
        return array();
    }

    public function getSubComponentVariations(array $module): array
    {
        return array_merge(
            parent::getSubComponentVariations($module),
            $this->getFrameoptionsSubmodules($module)
        );
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $module, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubComponentVariations($module) as $submodule) {
            $this->appendProp([$submodule], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($module);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($module);
        foreach ($this->getFrameoptionsSubmodules($module) as $submodule) {
            $this->appendProp([$submodule], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($submodule, $topframeoptions)) {
                $this->appendProp([$submodule], $props, 'class', 'top');
            } elseif (in_array($submodule, $bottomframeoptions)) {
                $this->appendProp([$submodule], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($module, $props);
    }
}
