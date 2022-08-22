<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const COMPONENT_TABLE_MYSTANCES = 'table-mystances';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABLE_MYSTANCES,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_TABLE_MYSTANCES => [UserStance_Module_Processor_TableInners::class, UserStance_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYSTANCES],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getHeaderTitles(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component->name) {
            case self::COMPONENT_TABLE_MYSTANCES:
                $ret[] = PoP_UserStance_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-userstance-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-userstance-processors');
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_TABLE_MYSTANCES:
                $this->appendProp($component, $props, 'class', 'table-mystances');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


