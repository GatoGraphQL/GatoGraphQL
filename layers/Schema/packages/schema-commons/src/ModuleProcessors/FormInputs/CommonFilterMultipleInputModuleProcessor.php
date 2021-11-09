<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors\FormInputs;

use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;

class CommonFilterMultipleInputModuleProcessor extends AbstractFilterInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_DATES = 'filterinput-dates';

    private ?FormInputHelperServiceInterface $formInputHelperService = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    final public function setFormInputHelperService(FormInputHelperServiceInterface $formInputHelperService): void
    {
        $this->formInputHelperService = $formInputHelperService;
    }
    final protected function getFormInputHelperService(): FormInputHelperServiceInterface
    {
        return $this->formInputHelperService ??= $this->instanceManager->getInstance(FormInputHelperServiceInterface::class);
    }
    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
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
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_DATES => ['from', 'to'],
            default => [],
        };
    }

    public function getName(array $module): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_DATES => 'date',
            default => parent::getName($module),
        };
    }

    // @todo Fix this, migrate to InputObjectType!
    // protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module): void
    // {
    //     // Replace the "date" item with "date-from" and "date-to"
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_DATES:
    //             $name = $this->getName($module);
    //             $subnames = $this->getInputSubnames($module);
    //             $dateFormat = 'Y-m-d';
    //             // Save documentation as template, and remove it
    //             $schemaDefinition = $schemaDefinitionItems[0];
    //             unset($schemaDefinition[SchemaDefinition::NAME]);
    //             unset($schemaDefinition[SchemaDefinition::DESCRIPTION]);
    //             array_shift($schemaDefinitionItems);
    //             // Add the other elements, using the original documentation as placeholder
    //             $schemaDefinitionItems[] = array_merge(
    //                 [
    //                     SchemaDefinition::NAME => $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[0]),
    //                 ],
    //                 $schemaDefinition,
    //                 [
    //                     SchemaDefinition::DESCRIPTION => sprintf(
    //                         $this->getTranslationAPI()->__('Search for elements starting from this date, in format \'%s\'', 'pop-engine'),
    //                         $dateFormat
    //                     ),
    //                 ]
    //             );
    //             $schemaDefinitionItems[] = array_merge(
    //                 [
    //                     SchemaDefinition::NAME => $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[1]),
    //                 ],
    //                 $schemaDefinition,
    //                 [
    //                     SchemaDefinition::DESCRIPTION => sprintf(
    //                         $this->getTranslationAPI()->__('Search for elements starting until this date, in format \'%s\'', 'pop-engine'),
    //                         $dateFormat
    //                     ),
    //                 ]
    //             );
    //             break;
    //     }
    // }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_DATES => $this->getDateScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                return sprintf(
                    $this->getTranslationAPI()->__('Search for elements between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\', in format \'%s\'', 'pop-engine'),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[0]),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[1]),
                    $this->getCmsService()->getOption($this->getNameResolver()->getName('popcms:option:dateFormat'))
                );
        }
        return null;
    }
}
