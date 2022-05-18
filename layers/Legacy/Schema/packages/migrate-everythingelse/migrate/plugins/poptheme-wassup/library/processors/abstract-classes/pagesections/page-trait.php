<?php

trait PoPTheme_Wassup_Module_Processor_PageTrait
{
    protected function getFrameoptionsSubmodules(array $component): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($component),
            $this->getFramebottomoptionsSubmodules($component)
        );
    }

    public function getFrametopoptionsSubmodules(array $component): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $component): array
    {
        return array();
    }

    public function getSubComponents(array $component): array
    {
        return array_merge(
            parent::getSubComponents($component),
            $this->getFrameoptionsSubmodules($component)
        );
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $component, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubComponents($component) as $subComponent) {
            $this->appendProp([$subComponent], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($component);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($component);
        foreach ($this->getFrameoptionsSubmodules($component) as $subComponent) {
            $this->appendProp([$subComponent], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($subComponent, $topframeoptions)) {
                $this->appendProp([$subComponent], $props, 'class', 'top');
            } elseif (in_array($subComponent, $bottomframeoptions)) {
                $this->appendProp([$subComponent], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($component, $props);
    }
}
