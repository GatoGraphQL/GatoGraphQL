<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\HelperServices\FormInputHelperServiceInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class FilterMultipleInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';

    protected ?FormInputHelperServiceInterface $formInputHelperService = null;
    protected ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    public function setFormInputHelperService(FormInputHelperServiceInterface $formInputHelperService): void
    {
        $this->formInputHelperService = $formInputHelperService;
    }
    protected function getFormInputHelperService(): FormInputHelperServiceInterface
    {
        return $this->formInputHelperService ??= $this->getInstanceManager()->getInstance(FormInputHelperServiceInterface::class);
    }
    public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->getInstanceManager()->getInstance(DateScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireFilterMultipleInputModuleProcessor(
        FormInputHelperServiceInterface $formInputHelperService,
        DateScalarTypeResolver $dateScalarTypeResolver,
    ): void {
        $this->formInputHelperService = $formInputHelperService;
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOSTDATES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputOptions(array $module): array
    {
        $options = parent::getInputOptions($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                $options['subnames'] = ['from', 'to'];
                break;
        }

        return $options;
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => 'date',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => $this->getDateScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                return sprintf(
                    $this->getTranslationAPI()->__('Search for posts between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\'', 'pop-posts'),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[0]),
                    $this->getFormInputHelperService()->getMultipleInputName($name, $subnames[1])
                );
        }
        return null;
    }
}
