<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class PoP_Module_Processor_MultiSelectFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    public final const FILTERINPUT_MODERATEDPOSTSTATUS = 'filterinput-moderatedpoststatus';
    public final const FILTERINPUT_UNMODERATEDPOSTSTATUS = 'filterinput-unmoderatedpoststatus';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_MODERATEDPOSTSTATUS],
            [self::class, self::FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );
    }

    protected function getQueryArgKey(array $filterInput): string
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::FILTERINPUT_UNMODERATEDPOSTSTATUS:
                $query['status'] = $value;
                break;
        }
    }
}



