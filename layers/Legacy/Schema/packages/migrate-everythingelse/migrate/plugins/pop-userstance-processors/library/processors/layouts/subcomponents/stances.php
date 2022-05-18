<?php

class UserStance_Module_Processor_StanceReferencedbyLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_STANCES = 'subcomponent-stances';
    public final const MODULE_LAZYSUBCOMPONENT_STANCES = 'lazysubcomponent-stances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_STANCES],
            [self::class, self::MODULE_LAZYSUBCOMPONENT_STANCES],
        );
    }

    public function getSubcomponentField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_STANCES:
                return 'stances';

            case self::MODULE_LAZYSUBCOMPONENT_STANCES:
                return 'stancesLazy';
        }
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_STANCES:
                $ret[] = [UserStance_Module_Processor_LayoutContents::class, UserStance_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_STANCES];
                break;

            case self::MODULE_LAZYSUBCOMPONENT_STANCES:
                $ret[] = [UserStance_Module_Processor_LayoutContents::class, UserStance_Module_Processor_LayoutContents::MODULE_CONTENTLAYOUT_STANCES_APPENDABLE];
                break;
        }

        return $ret;
    }

    public function isIndividual(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_STANCES:
            case self::MODULE_LAZYSUBCOMPONENT_STANCES:
                return false;
        }

        return parent::isIndividual($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_STANCES:
            case self::MODULE_LAZYSUBCOMPONENT_STANCES:
                $this->appendProp($componentVariation, $props, 'class', 'referencedby clearfix');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



