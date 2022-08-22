<?php

class GD_URE_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_ORGANIZATIONS_DETAILS = 'scroll-organizations-details';
    public final const COMPONENT_SCROLL_INDIVIDUALS_DETAILS = 'scroll-individuals-details';
    public final const COMPONENT_SCROLL_ORGANIZATIONS_FULLVIEW = 'scroll-organizations-fullview';
    public final const COMPONENT_SCROLL_INDIVIDUALS_FULLVIEW = 'scroll-individuals-fullview';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_ORGANIZATIONS_DETAILS,
            self::COMPONENT_SCROLL_INDIVIDUALS_DETAILS,
            self::COMPONENT_SCROLL_ORGANIZATIONS_FULLVIEW,
            self::COMPONENT_SCROLL_INDIVIDUALS_FULLVIEW,
        );
    }


    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_ORGANIZATIONS_DETAILS => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_ORGANIZATIONS_DETAILS],
            self::COMPONENT_SCROLL_INDIVIDUALS_DETAILS => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_INDIVIDUALS_DETAILS],
            self::COMPONENT_SCROLL_ORGANIZATIONS_FULLVIEW => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_ORGANIZATIONS_FULLVIEW],
            self::COMPONENT_SCROLL_INDIVIDUALS_FULLVIEW => [GD_URE_Module_Processor_CustomScrollInners::class, GD_URE_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_INDIVIDUALS_FULLVIEW],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Extra classes
        $details = array(
            self::COMPONENT_SCROLL_ORGANIZATIONS_DETAILS,
            self::COMPONENT_SCROLL_INDIVIDUALS_DETAILS,
        );
        $fullviews = array(
            self::COMPONENT_SCROLL_ORGANIZATIONS_FULLVIEW,
            self::COMPONENT_SCROLL_INDIVIDUALS_FULLVIEW,
        );

        $extra_class = '';
        if (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($component, $details)) {
            $extra_class = 'details';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


