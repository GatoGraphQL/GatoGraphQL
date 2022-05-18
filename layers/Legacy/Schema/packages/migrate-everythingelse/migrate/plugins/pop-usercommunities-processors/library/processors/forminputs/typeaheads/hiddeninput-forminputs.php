<?php

class GD_URE_Processor_SelectableHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES = 'forminput-hiddeninput-selectablelayoutusercommunities';
    public final const MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES = 'filterinput-hiddeninput-selectablelayoutcommunities';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES],
            [self::class, self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES],
        );
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES:
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES:
                $names = array(
                    self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES => 'communities',
                );
                return $names[$module[1]];
        }
        
        return parent::getName($module);
    }
}

