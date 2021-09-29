<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors\FormInputs;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Facades\HelperServices\FormInputHelperServiceFacade;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class CommonFilterMultipleInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_DATES = 'filterinput-dates';
    protected FormInputHelperServiceInterface $formInputHelperService;

    #[Required]
    public function autowireCommonFilterMultipleInputModuleProcessor(
        FormInputHelperServiceInterface $formInputHelperService,
    ): void {
        $this->formInputHelperService = $formInputHelperService;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_DATES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_DATES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_DATES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputSubnames(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                return ['from', 'to'];
        }
        return [];
    }

    public function getName(array $module): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        $names = array(
            self::MODULE_FILTERINPUT_DATES => 'date',
        );
        return $names[$module[1]] ?? parent::getName($module);
    }

    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module): void
    {
        // Replace the "date" item with "date-from" and "date-to"
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $name = $this->getName($module);
                $subnames = $this->getInputSubnames($module);
                $dateFormat = 'Y-m-d';
                // Save documentation as template, and remove it
                $schemaDefinition = $schemaDefinitionItems[0];
                unset($schemaDefinition[SchemaDefinition::ARGNAME_NAME]);
                unset($schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION]);
                array_shift($schemaDefinitionItems);
                // Add the other elements, using the original documantation as placeholder
                $schemaDefinitionItems[] = array_merge(
                    [
                        SchemaDefinition::ARGNAME_NAME => $this->formInputHelperService->getMultipleInputName($name, $subnames[0]),
                    ],
                    $schemaDefinition,
                    [
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('Search for elements starting from this date, in format \'%s\'', 'pop-engine'),
                            $dateFormat
                        ),
                    ]
                );
                $schemaDefinitionItems[] = array_merge(
                    [
                        SchemaDefinition::ARGNAME_NAME => $this->formInputHelperService->getMultipleInputName($name, $subnames[1]),
                    ],
                    $schemaDefinition,
                    [
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('Search for elements starting until this date, in format \'%s\'', 'pop-engine'),
                            $dateFormat
                        ),
                    ]
                );
                break;
        }
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_DATES => SchemaDefinition::TYPE_DATE,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                return sprintf(
                    $this->translationAPI->__('Search for elements between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\', in format \'%s\'', 'pop-engine'),
                    $this->formInputHelperService->getMultipleInputName($name, $subnames[0]),
                    $this->formInputHelperService->getMultipleInputName($name, $subnames[1]),
                    $this->cmsService->getOption($this->nameResolver->getName('popcms:option:dateFormat'))
                );
        }
        return null;
    }
}
