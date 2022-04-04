<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ModuleProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor as CustomPostFilterInputModuleProcessor;
use PoPCMSSchema\GenericCustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class CommonCustomPostFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public final const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

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

    public function getFilterInputModules(array $module): array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_GENERICTYPE => [
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE => [
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG],
                [CustomPostFilterInputModuleProcessor::class, CustomPostFilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($module, $fieldArgName);
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE:
            case self::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE:
                $slugFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG
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
