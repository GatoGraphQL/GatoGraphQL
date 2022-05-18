<?php

class PoP_AddHighlights_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST = 'forminput-hiddeninput-highlightedpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST],
        );
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST:
                return POP_INPUTNAME_HIGHLIGHTEDPOST;
        }

        return parent::getName($componentVariation);
    }
}

