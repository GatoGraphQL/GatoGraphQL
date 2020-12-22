<?php
class PoP_Events_Module_Processor_FilterInputProcessor extends \PoP\ComponentModel\AbstractFilterInputProcessor
{
    public const FILTERINPUT_EVENTSCOPE = 'filterinput-eventscope';

    public function getFilterInputsToProcess()
    {
        return array(
            [self::class, self::FILTERINPUT_EVENTSCOPE],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value)
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_EVENTSCOPE:
                $query['scope'] = $value['from'].','.$value['to'];
                break;
        }
    }
}



