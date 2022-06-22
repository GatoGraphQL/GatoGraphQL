<?php

class GD_Processor_SelectableLocationHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS = 'forminput-hiddeninput-selectablelayoutlocations';
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION = 'forminput-hiddeninput-selectablelayoutlocation';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS,
            self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION,
        );
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS:
                return true;
        }

        return parent::isMultiple($component);
    }
}
