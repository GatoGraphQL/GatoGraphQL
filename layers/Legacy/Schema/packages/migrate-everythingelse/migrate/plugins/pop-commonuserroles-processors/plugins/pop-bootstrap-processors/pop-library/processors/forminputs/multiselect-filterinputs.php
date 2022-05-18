<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class GD_URE_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS = 'filterinput-individualinterests';
    public final const MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES = 'filterinput-organizationcategories';
    public final const MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES = 'filterinput-organizationtypes';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS],
            [self::class, self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_INDIVIDUALINTERESTS],
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFilterInputProcessor::class, GD_URE_Module_Processor_MultiSelectFilterInputProcessor::URE_FILTERINPUT_ORGANIZATIONTYPES],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
    //         case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
    //         case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:categories',
                    TranslationAPIFacade::getInstance()->__('Organization Categories', 'poptheme-wassup')
                );

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:types',
                    TranslationAPIFacade::getInstance()->__('Organization Types', 'poptheme-wassup')
                );
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return GD_FormInput_IndividualInterests::class;

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return GD_FormInput_OrganizationCategories::class;

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return GD_FormInput_OrganizationTypes::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return 'interests';

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return 'categories';

            case self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return 'types';
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => $this->stringScalarTypeResolver,
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => $this->stringScalarTypeResolver,
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS,
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES,
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



