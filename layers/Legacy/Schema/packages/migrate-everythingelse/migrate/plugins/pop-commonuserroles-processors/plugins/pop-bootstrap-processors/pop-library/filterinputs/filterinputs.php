<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class GD_URE_Module_Processor_MultiSelectFilterInputProcessor extends AbstractFilterInputProcessor
{
    public const URE_FILTERINPUT_INDIVIDUALINTERESTS = 'filterinput-individualinterests';
    public const URE_FILTERINPUT_ORGANIZATIONCATEGORIES = 'filterinput-organizationcategories';
    public const URE_FILTERINPUT_ORGANIZATIONTYPES = 'filterinput-organizationtypes';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::URE_FILTERINPUT_INDIVIDUALINTERESTS],
            [self::class, self::URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::URE_FILTERINPUT_ORGANIZATIONTYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
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



