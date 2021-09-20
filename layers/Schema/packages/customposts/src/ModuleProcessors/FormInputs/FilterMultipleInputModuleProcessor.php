<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors\FormInputs;

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
use PoPSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;

class FilterMultipleInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    use FormMultipleInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ModulePathHelpersInterface $modulePathHelpers,
        ModuleFilterManagerInterface $moduleFilterManager,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        CMSServiceInterface $cmsService,
        NameResolverInterface $nameResolver,
        DataloadHelperServiceInterface $dataloadHelperService,
        RequestHelperServiceInterface $requestHelperService,
        ModulePaths $modulePaths,
        protected FormInputHelperServiceInterface $formInputHelperService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $modulePathHelpers,
            $moduleFilterManager,
            $moduleProcessorManager,
            $cmsService,
            $nameResolver,
            $dataloadHelperService,
            $requestHelperService,
            $modulePaths,
        );
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

    public function getSchemaFilterInputType(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => SchemaDefinition::TYPE_DATE,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                return sprintf(
                    $this->translationAPI->__('Search for posts between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\'', 'pop-posts'),
                    $this->formInputHelperService->getMultipleInputName($name, $subnames[0]),
                    $this->formInputHelperService->getMultipleInputName($name, $subnames[1])
                );
        }
        return null;
    }
}
