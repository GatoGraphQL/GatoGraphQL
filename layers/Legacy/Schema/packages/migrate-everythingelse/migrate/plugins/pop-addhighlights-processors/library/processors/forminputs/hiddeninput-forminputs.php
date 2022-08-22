<?php

class PoP_AddHighlights_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST = 'forminput-hiddeninput-highlightedpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST,
        );
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST:
                return POP_INPUTNAME_HIGHLIGHTEDPOST;
        }

        return parent::getName($component);
    }
}

