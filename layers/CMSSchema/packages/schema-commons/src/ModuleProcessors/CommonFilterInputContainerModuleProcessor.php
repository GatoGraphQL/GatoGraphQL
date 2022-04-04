<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ModuleProcessors;

use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class CommonFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public final const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID = 'filterinputcontainer-entity-by-id';
    public final const MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG = 'filterinputcontainer-entity-by-slug';
    public final const MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING = 'filterinputcontainer-date-as-string';
    public final const MODULE_FILTERINPUTCONTAINER_GMTDATE = 'filterinputcontainer-utcdate';
    public final const MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING = 'filterinputcontainer-utcdate-as-string';

    private ?CMSServiceInterface $cmsService = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_GMTDATE],
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
            self::MODULE_FILTERINPUTCONTAINER_GMTDATE => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_GMT],
            ],
            self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_DATEFORMAT],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_GMT],
            ],
            default => [],
        };
    }

    public function getFieldFilterInputDefaultValue(array $module, string $fieldArgName): mixed
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING:
            case self::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $formatFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_DATEFORMAT
                ]);
                if ($fieldArgName === $formatFilterInputName) {
                    return $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:dateFormat'));
                }
                break;
        }
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_GMTDATE:
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
        return parent::getFieldFilterInputDefaultValue($module, $fieldArgName);
    }

    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($module, $fieldArgName);
        switch ($module[1]) {
            case self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID:
                $idFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID
                ]);
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG:
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
