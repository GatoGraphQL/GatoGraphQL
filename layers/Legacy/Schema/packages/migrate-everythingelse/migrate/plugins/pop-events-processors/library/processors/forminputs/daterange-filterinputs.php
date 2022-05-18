<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Events_Module_Processor_DateRangeComponentFilterInputs extends PoP_Module_Processor_DateRangeFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_EVENTSCOPE = 'filterinput-eventscope';

    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_EVENTSCOPE],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_EVENTSCOPE => [PoP_Events_Module_Processor_FilterInputProcessor::class, PoP_Events_Module_Processor_FilterInputPrDATEsor::FILTERINPUT_EVENTSCOPE],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUT_EVENTSCOPE:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_EVENTSCOPE => 'scope',
                );
                return $names[$componentVariation[1]];
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERINPUT_EVENTSCOPE => $this->dateScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_EVENTSCOPE => $translationAPI->__('', ''),
            default => null,
        };
    }
}



