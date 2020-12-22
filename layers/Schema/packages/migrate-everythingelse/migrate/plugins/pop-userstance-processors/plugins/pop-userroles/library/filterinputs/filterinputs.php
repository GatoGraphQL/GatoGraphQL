<?php
class PoP_Module_Processor_UserStanceUserRolesFilterInputProcessor extends \PoP\ComponentModel\AbstractFilterInputProcessor
{
    public const FILTERINPUT_AUTHORROLE_MULTISELECT = 'filterinput-multiselect-authorrole';

    public function getFilterInputsToProcess()
    {
        return array(
            [self::class, self::FILTERINPUT_AUTHORROLE_MULTISELECT],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value)
    {
        switch ($filterInput[1]) {

            case self::FILTERINPUT_AUTHORROLE_MULTISELECT:
                $query['meta-query'][] = [
                    'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_URE_METAKEY_POST_AUTHORROLE),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}
