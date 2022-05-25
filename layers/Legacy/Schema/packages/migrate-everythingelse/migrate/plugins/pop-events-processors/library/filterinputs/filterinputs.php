<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class PoP_Events_Module_Processor_FilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    public final const FILTERINPUT_EVENTSCOPE = 'filterinput-eventscope';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_EVENTSCOPE],
        );
    }

    protected function getQueryArgKey(array $filterInput): string
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_EVENTSCOPE:
                $query['scope'] = $value['from'].','.$value['to'];
                break;
        }
    }
}



