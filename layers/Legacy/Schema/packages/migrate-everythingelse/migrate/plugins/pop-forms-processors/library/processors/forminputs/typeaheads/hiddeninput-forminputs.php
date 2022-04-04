<?php

class GD_Processor_SelectableHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES = 'forminput-hiddeninput-selectablereferences';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES = 'forminput-hiddeninput-selectablelayoutuserrofiles';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS = 'forminput-hiddeninput-selectablelayoutauthors';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS = 'forminput-hiddeninput-selectablelayoutcoauthors';
    public final const MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES = 'filterinput-hiddeninput-selectablelayoutprofiles';
    public final const MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS = 'filterinput-hiddeninput-selectablelayoutcommunityusers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS],
            [self::class, self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES],
            [self::class, self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS],
        );
    }

    public function isMultiple(array $module): bool
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES:
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES:
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS:
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS:
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES:
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS:
                return true;
        }

        return parent::isMultiple($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
         // case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES:
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES:
            case self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS:
                $names = array(
                    // self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES => POP_INPUTNAME_REFERENCES,
                    self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES => 'profiles',
                    self::MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS => 'communityusers',
                );
                return $names[$module[1]];
        }
        
        return parent::getName($module);
    }
}

