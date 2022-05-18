<?php

class GD_Processor_SelectableLocationHiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS = 'forminput-hiddeninput-selectablelayoutlocations';
    public final const MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION = 'forminput-hiddeninput-selectablelayoutlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATION],
        );
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_SELECTABLELAYOUTLOCATIONS:
                return true;
        }

        return parent::isMultiple($component);
    }
}
