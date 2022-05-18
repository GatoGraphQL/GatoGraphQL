<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYEVENTS = 'table-myevents';
    public final const MODULE_TABLE_MYPASTEVENTS = 'table-mypastevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYEVENTS],
            [self::class, self::MODULE_TABLE_MYPASTEVENTS],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYEVENTS:
            case self::MODULE_TABLE_MYPASTEVENTS:
                $inners = array(
                    self::MODULE_TABLE_MYEVENTS => [GD_EM_Module_Processor_TableInners::class, GD_EM_Module_Processor_TableInners::MODULE_TABLEINNER_MYEVENTS],
                    self::MODULE_TABLE_MYPASTEVENTS => [GD_EM_Module_Processor_TableInners::class, GD_EM_Module_Processor_TableInners::MODULE_TABLEINNER_MYPASTEVENTS],
                );

                return $inners[$componentVariation[1]];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getHeaderTitles(array $componentVariation)
    {
        $ret = parent::getHeaderTitles($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_TABLE_MYEVENTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('When', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;

            case self::MODULE_TABLE_MYPASTEVENTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Past Event', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('When', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;
        }
    
        return $ret;
    }
}


