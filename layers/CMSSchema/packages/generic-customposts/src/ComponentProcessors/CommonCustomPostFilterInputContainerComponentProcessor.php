<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\GenericCustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommonCustomPostFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE = 'filterinputcontainer-custompost-by-generictype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-status-generictype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE = 'filterinputcontainer-custompost-by-id-generictype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-id-status-generictype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE = 'filterinputcontainer-custompost-by-slug-generictype';
    public final const MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE = 'filterinputcontainer-custompost-by-slug-status-generictype';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE],
        );
    }

    public function getFilterInputModules(array $componentVariation): array
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE => [
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE => [
                [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID],
                [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE => [
                [CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_SLUG],
                [CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputComponentProcessor::class, FilterInputComponentProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(array $componentVariation, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($componentVariation, $fieldArgName);
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::MODULE_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE:
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
