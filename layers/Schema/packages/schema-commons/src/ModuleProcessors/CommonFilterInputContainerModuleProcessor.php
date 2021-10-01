<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class CommonFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID = 'filterinputcontainer-entity-by-id';
    public const MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG = 'filterinputcontainer-entity-by-slug';
    public const MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING = 'filterinputcontainer-date-as-string';
    public const MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING = 'filterinputcontainer-utcdate-as-string';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG],
            ],
            self::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_DATEFORMAT],
            ],
            self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_DATEFORMAT],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_GMT],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputMandatoryArgs(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID
                ]);
                return [
                    $idFilterInputName,
                ];
            case self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG:
                $slugFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUG
                ]);
                return [
                    $slugFilterInputName,
                ];
        }
        return parent::getFieldFilterInputMandatoryArgs($module);
    }

    public function getSchemaFieldArgDefaultValue(array $module, string $fieldArgName): mixed
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING:
            case self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $formatFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_DATEFORMAT
                ]);
                if ($fieldArgName === $formatFilterInputName) {
                    return $this->cmsService->getOption($this->nameResolver->getName('popcms:option:dateFormat'));
                }
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $gmtFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_GMT
                ]);
                if ($fieldArgName === $gmtFilterInputName) {
                    return false;
                }
                break;
        }
        return parent::getSchemaFieldArgDefaultValue($module, $fieldArgName);
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
