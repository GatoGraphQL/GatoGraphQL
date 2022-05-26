<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class GD_URE_Module_Processor_MultiSelectFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    public final const URE_FILTERINPUT_INDIVIDUALINTERESTS = 'filterinput-individualinterests';
    public final const URE_FILTERINPUT_ORGANIZATIONCATEGORIES = 'filterinput-organizationcategories';
    public final const URE_FILTERINPUT_ORGANIZATIONTYPES = 'filterinput-organizationtypes';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::URE_FILTERINPUT_INDIVIDUALINTERESTS],
            [self::class, self::URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::URE_FILTERINPUT_ORGANIZATIONTYPES],
        );
    }

    /**
     * @todo Split this class into multiple ones, returning a single string per each ($filterInput is not valid anymore)
     */
    protected function getQueryArgKey(): string
    {
        switch ($filterInput[1]) {

            case self::URE_FILTERINPUT_INDIVIDUALINTERESTS:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::URE_FILTERINPUT_ORGANIZATIONTYPES:
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}



