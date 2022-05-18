<?php

class GD_Processor_SelectableHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES = 'forminput-hiddeninput-selectablereferences';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES = 'forminput-hiddeninput-selectablelayoutuserrofiles';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS = 'forminput-hiddeninput-selectablelayoutauthors';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS = 'forminput-hiddeninput-selectablelayoutcoauthors';
    public final const MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES = 'filterinput-hiddeninput-selectablelayoutprofiles';
    public final const MODULE_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS = 'filterinput-hiddeninput-selectablelayoutcommunityusers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS],
            [self::class, self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES],
            [self::class, self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS],
        );
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES:
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES:
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS:
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
         // case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS:
                $names = array(
                    // self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES => POP_INPUTNAME_REFERENCES,
                    self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES => 'profiles',
                    self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS => 'communityusers',
                );
                return $names[$component[1]];
        }
        
        return parent::getName($component);
    }
}

