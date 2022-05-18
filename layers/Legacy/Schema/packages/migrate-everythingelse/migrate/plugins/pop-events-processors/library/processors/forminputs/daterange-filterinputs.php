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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_EVENTSCOPE],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_EVENTSCOPE => [PoP_Events_Module_Processor_FilterInputProcessor::class, PoP_Events_Module_Processor_FilterInputPrDATEsor::FILTERINPUT_EVENTSCOPE],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_FILTERINPUT_EVENTSCOPE:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                return TranslationAPIFacade::getInstance()->__('Dates', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_EVENTSCOPE:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_EVENTSCOPE => 'scope',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_EVENTSCOPE => $this->dateScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_EVENTSCOPE => $translationAPI->__('', ''),
            default => null,
        };
    }
}



