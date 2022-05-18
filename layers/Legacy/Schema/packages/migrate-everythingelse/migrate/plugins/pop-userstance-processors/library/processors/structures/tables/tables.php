<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const COMPONENT_TABLE_MYSTANCES = 'table-mystances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLE_MYSTANCES],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_TABLE_MYSTANCES => [UserStance_Module_Processor_TableInners::class, UserStance_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYSTANCES],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function getHeaderTitles(array $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYSTANCES:
                $ret[] = PoP_UserStance_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-userstance-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-userstance-processors');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYSTANCES:
                $this->appendProp($component, $props, 'class', 'table-mystances');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


