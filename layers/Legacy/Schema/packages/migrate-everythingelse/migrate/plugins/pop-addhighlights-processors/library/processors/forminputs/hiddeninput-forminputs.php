<?php

class PoP_AddHighlights_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public const MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST = 'forminput-hiddeninput-highlightedpost';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST],
        );
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_HIGHLIGHTEDPOST:
                return POP_INPUTNAME_HIGHLIGHTEDPOST;
        }

        return parent::getName($module);
    }
}

