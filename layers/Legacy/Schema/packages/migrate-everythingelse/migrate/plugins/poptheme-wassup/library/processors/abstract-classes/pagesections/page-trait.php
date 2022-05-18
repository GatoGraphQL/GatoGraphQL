<?php

trait PoPTheme_Wassup_Module_Processor_PageTrait
{
    protected function getFrameoptionsSubmodules(array $componentVariation): array
    {
        return array_merge(
            $this->getFrametopoptionsSubmodules($componentVariation),
            $this->getFramebottomoptionsSubmodules($componentVariation)
        );
    }

    public function getFrametopoptionsSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getFramebottomoptionsSubmodules(array $componentVariation): array
    {
        return array();
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array_merge(
            parent::getSubComponentVariations($componentVariation),
            $this->getFrameoptionsSubmodules($componentVariation)
        );
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // All blocks added under the pageSection can have class "pop-outerblock"
        foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
            $this->appendProp([$subComponentVariation], $props, 'class', 'pop-outerblock');
        }

        $topframeoptions = $this->getFrametopoptionsSubmodules($componentVariation);
        $bottomframeoptions = $this->getFramebottomoptionsSubmodules($componentVariation);
        foreach ($this->getFrameoptionsSubmodules($componentVariation) as $subComponentVariation) {
            $this->appendProp([$subComponentVariation], $props, 'class', 'blocksection-controls pull-right');

            if (in_array($subComponentVariation, $topframeoptions)) {
                $this->appendProp([$subComponentVariation], $props, 'class', 'top');
            } elseif (in_array($subComponentVariation, $bottomframeoptions)) {
                $this->appendProp([$subComponentVariation], $props, 'class', 'bottom');
            }
        }

        parent::initModelProps($componentVariation, $props);
    }
}
