<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYSTANCES = 'table-mystances';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYSTANCES],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_TABLE_MYSTANCES => [UserStance_Module_Processor_TableInners::class, UserStance_Module_Processor_TableInners::MODULE_TABLEINNER_MYSTANCES],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function getHeaderTitles(array $module)
    {
        $ret = parent::getHeaderTitles($module);

        switch ($module[1]) {
            case self::MODULE_TABLE_MYSTANCES:
                $ret[] = PoP_UserStance_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-userstance-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-userstance-processors');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_TABLE_MYSTANCES:
                $this->appendProp($module, $props, 'class', 'table-mystances');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


