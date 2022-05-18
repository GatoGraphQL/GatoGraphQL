<?php

class PoP_ContentPostLinksCreation_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW = 'scroll-mylinks-simpleviewpreview';
    public final const MODULE_SCROLL_MYLINKS_FULLVIEWPREVIEW = 'scroll-mylinks-fullviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYLINKS_FULLVIEWPREVIEW],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYLINKS_SIMPLEVIEWPREVIEW],
            self::COMPONENT_SCROLL_MYLINKS_FULLVIEWPREVIEW => [PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinksCreation_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYLINKS_FULLVIEWPREVIEW],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::COMPONENT_SCROLL_MYLINKS_SIMPLEVIEWPREVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_SCROLL_MYLINKS_FULLVIEWPREVIEW],
        );

        $extra_class = '';
        if (in_array($component, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


