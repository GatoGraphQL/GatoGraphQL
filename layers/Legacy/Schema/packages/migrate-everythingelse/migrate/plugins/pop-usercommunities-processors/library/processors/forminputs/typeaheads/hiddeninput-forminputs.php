<?php

class GD_URE_Processor_SelectableHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES = 'forminput-hiddeninput-selectablelayoutusercommunities';
    public final const COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES = 'filterinput-hiddeninput-selectablelayoutcommunities';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES],
            [self::class, self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES],
        );
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTUSERCOMMUNITIES:
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES:
                $names = array(
                    self::COMPONENT_FILTERINPUT_HIDDENINPUT_SELECTABLELAYOUTCOMMUNITIES => 'communities',
                );
                return $names[$component[1]];
        }
        
        return parent::getName($component);
    }
}

