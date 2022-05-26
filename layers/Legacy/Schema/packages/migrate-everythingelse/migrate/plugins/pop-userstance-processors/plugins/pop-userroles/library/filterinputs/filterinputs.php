<?php
use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class PoP_Module_Processor_UserStanceUserRolesFilterInput extends AbstractValueToQueryFilterInput
{
    public final const FILTERINPUT_AUTHORROLE_MULTISELECT = 'filterinput-multiselect-authorrole';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_AUTHORROLE_MULTISELECT],
        );
    }

    /**
     * @todo Split this class into multiple ones, returning a single string per each ($filterInput is not valid anymore)
     */
    protected function getQueryArgKey(): string
    {
        switch ($filterInput[1]) {

            case self::FILTERINPUT_AUTHORROLE_MULTISELECT:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\CustomPostMeta\Utils::getMetaKey(GD_URE_METAKEY_POST_AUTHORROLE),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}
