<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ComponentProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommonCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS = 'filterinputcontainer-custompoststatus';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE = 'filterinputcontainer-custompost-by-uniontype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-status-uniontype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS = 'filterinputcontainer-custompost-by-id-status';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE = 'filterinputcontainer-custompost-by-id-uniontype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-id-status-uniontype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS = 'filterinputcontainer-custompost-by-slug-status';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-uniontype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE = 'filterinputcontainer-custompost-by-slug-status-uniontype';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_UNIONTYPE => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($module, $fieldArgName);
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_UNIONTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_UNIONTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_UNIONTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_UNIONTYPE:
                $slugFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG
                ]);
                if ($fieldArgName === $slugFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
        }
        return $fieldFilterInputTypeModifiers;
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}
