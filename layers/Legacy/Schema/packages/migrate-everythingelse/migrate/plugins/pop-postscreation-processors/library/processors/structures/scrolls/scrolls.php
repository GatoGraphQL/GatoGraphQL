<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW = 'scroll-mylinks-simpleviewpreview';
    public final const MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW = 'scroll-mylinks-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW],
        );
    }


    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW],
            self::MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW],
        );

        $extra_class = '';
        if (in_array($componentVariation, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($componentVariation, $fullviews)) {
            $extra_class = 'fullview';
        }
        $this->appendProp($componentVariation, $props, 'class', $extra_class);

        parent::initModelProps($componentVariation, $props);
    }
}


