<?php

trait PoPTheme_Wassup_Module_Processor_PageTrait
{
    protected function getFrameoptionsSubcomponents(array $component): array
    {
        return array_merge(
            $this->getFrametopoptionsSubcomponents($component),
            $this->getFramebottomoptionsSubcomponents($component)
        );
    }

    public function getFrametopoptionsSubcomponents(array $component): array
    {
        return array();
    }

    public function getFramebottomoptionsSubcomponents(array $component): array
    {
        return array();
    }

    public function getSubcomponents(array $component): array
    {
        return array_merge(
            parent::getSubcomponents($component),
            $this->getFrameoptionsSubcomponents($component)
        );
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $component, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubcomponents($component) as $subComponent) {
            $this->appendProp([$subComponent], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubcomponents($component);
        $bottomframeoptions = $this->getFramebottomoptionsSubcomponents($component);
        foreach ($this->getFrameoptionsSubcomponents($component) as $subComponent) {
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
