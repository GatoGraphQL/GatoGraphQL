<?php
namespace PoPSchema\Users;

class FilterInputProcessor extends \PoP\ComponentModel\AbstractFilterInputProcessor
{
    public const FILTERINPUT_NAME = 'filterinput-name';
    public const FILTERINPUT_EMAILS = 'filterinput-emails';

    public function getFilterInputsToProcess()
    {
        return array(
            [self::class, self::FILTERINPUT_NAME],
            [self::class, self::FILTERINPUT_EMAILS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value)
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_NAME:
                $query['name'] = $value;
                break;
            case self::FILTERINPUT_EMAILS:
                $query['emails'] = $value;
                break;
        }
    }
}



