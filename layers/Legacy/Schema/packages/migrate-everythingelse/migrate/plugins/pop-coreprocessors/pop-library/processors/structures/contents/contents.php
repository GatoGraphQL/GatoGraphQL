<?php

use PoP\ComponentModel\State\ApplicationState;

class PoPCore_Module_Processor_Contents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'content-postconclusionsidebar-horizontal';
    public final const MODULE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'content-subjugatedpostconclusionsidebar-horizontal';
    public final const MODULE_CONTENT_LATESTCOUNTS = 'content-latestcounts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_CONTENT_LATESTCOUNTS],
        );
    }
    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoPCore_Module_Processor_SingleContentInners::class, PoPCore_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            self::MODULE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => [PoPCore_Module_Processor_SingleContentInners::class, PoPCore_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
            self::MODULE_CONTENT_LATESTCOUNTS => [PoPCore_Module_Processor_MultipleContentInners::class, PoPCore_Module_Processor_MultipleContentInners::MODULE_CONTENTINNER_LATESTCOUNTS],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::MODULE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $this->appendProp($componentVariation, $props, 'class', 'conclusion horizontal sidebar');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


