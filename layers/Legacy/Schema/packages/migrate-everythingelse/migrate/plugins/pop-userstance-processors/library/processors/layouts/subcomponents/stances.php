<?php

class UserStance_Module_Processor_StanceReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const COMPONENT_SUBCOMPONENT_STANCES = 'subcomponent-stances';
    public final const COMPONENT_LAZYSUBCOMPONENT_STANCES = 'lazysubcomponent-stances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBCOMPONENT_STANCES],
            [self::class, self::COMPONENT_LAZYSUBCOMPONENT_STANCES],
        );
    }

    public function getSubcomponentField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_STANCES:
                return 'stances';

            case self::COMPONENT_LAZYSUBCOMPONENT_STANCES:
                return 'stancesLazy';
        }
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_STANCES:
                $ret[] = [UserStance_Module_Processor_LayoutContents::class, UserStance_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_STANCES];
                break;

            case self::COMPONENT_LAZYSUBCOMPONENT_STANCES:
                $ret[] = [UserStance_Module_Processor_LayoutContents::class, UserStance_Module_Processor_LayoutContents::COMPONENT_CONTENTLAYOUT_STANCES_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_STANCES:
            case self::COMPONENT_LAZYSUBCOMPONENT_STANCES:
                return false;
        }

        return parent::isIndividual($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_SUBCOMPONENT_STANCES:
            case self::COMPONENT_LAZYSUBCOMPONENT_STANCES:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



