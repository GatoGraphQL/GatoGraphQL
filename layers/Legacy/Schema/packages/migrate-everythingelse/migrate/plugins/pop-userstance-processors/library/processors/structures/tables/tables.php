<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYSTANCES = 'table-mystances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYSTANCES],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_TABLE_MYSTANCES => [UserStance_Module_Processor_TableInners::class, UserStance_Module_Processor_TableInners::MODULE_TABLEINNER_MYSTANCES],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getHeaderTitles(array $componentVariation)
    {
        $ret = parent::getHeaderTitles($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYSTANCES:
                $ret[] = PoP_UserStance_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-userstance-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-userstance-processors');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYSTANCES:
                $this->appendProp($componentVariation, $props, 'class', 'table-mystances');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


