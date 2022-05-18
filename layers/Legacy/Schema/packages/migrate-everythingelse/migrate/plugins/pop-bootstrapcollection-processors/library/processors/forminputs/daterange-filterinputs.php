<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_DateRangeComponentFilterInputs extends PoP_Module_Processor_DateRangeFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_CUSTOMPOSTDATES = 'filterinput-custompostdates';

    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

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

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTDATES:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
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
        return match($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => $this->dateScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTDATES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



