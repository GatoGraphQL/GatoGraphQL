<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs;

use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\Engine\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;

class CommonFilterMultipleInputModuleProcessor extends AbstractFilterInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_DATES = 'filterinput-dates';

    private ?FormInputHelperServiceInterface $formInputHelperService = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;
    private ?CMSServiceInterface $cmsService = null;

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
                    $this->__('Search for elements between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\', in format \'%s\'', 'pop-engine'),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[0]),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[1]),
                    $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:dateFormat'))
                );
        }
        return null;
    }
}
