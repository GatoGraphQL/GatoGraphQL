<?php

class PoP_AddHighlights_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST = 'forminput-hiddeninput-highlightedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST],
        );
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST:
                return POP_INPUTNAME_HIGHLIGHTEDPOST;
        }

        return parent::getName($component);
    }
}

