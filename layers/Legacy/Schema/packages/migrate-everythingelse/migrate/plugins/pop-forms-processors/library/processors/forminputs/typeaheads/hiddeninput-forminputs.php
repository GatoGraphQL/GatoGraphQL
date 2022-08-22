<?php

class GD_Processor_SelectableHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES = 'forminput-hiddeninput-selectablereferences';
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES = 'forminput-hiddeninput-selectablelayoutuserrofiles';
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS = 'forminput-hiddeninput-selectablelayoutauthors';
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS = 'forminput-hiddeninput-selectablelayoutcoauthors';
    public final const COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES = 'filterinput-hiddeninput-selectablelayoutprofiles';
    public final const COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS = 'filterinput-hiddeninput-selectablelayoutcommunityusers';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES,
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERPROFILES,
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTAUTHORS,
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTCOAUTHORS,
            self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES,
            self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS,
        );
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
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

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
         // case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS:
                $names = array(
                    // self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLEREFERENCES => POP_INPUTNAME_REFERENCES,
                    self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTPROFILES => 'profiles',
                    self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITYUSERS => 'communityusers',
                );
                return $names[$component->name];
        }
        
        return parent::getName($component);
    }
}

