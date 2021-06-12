<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class PoP_Module_Processor_MultiSelectFilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_MODERATEDPOSTSTATUS = 'filterinput-moderatedpoststatus';
    public const FILTERINPUT_UNMODERATEDPOSTSTATUS = 'filterinput-unmoderatedpoststatus';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_MODERATEDPOSTSTATUS],
            [self::class, self::FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::FILTERINPUT_UNMODERATEDPOSTSTATUS:
                $query['status'] = $value;
                break;
        }
    }
}



